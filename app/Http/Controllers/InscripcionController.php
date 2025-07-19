<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membresia;
use App\Models\Cliente;
use App\Models\Inscripcion;
use App\Models\DetalleInscripcion;
use App\Models\Servicio;
use App\Models\Seccion;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Requests\StoreInscripcionRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class InscripcionController extends Controller
{



    public function create()
    {
        // Obtener las membresías no eliminadas y vigentes
        $membresias = Membresia::where('eliminado', 1)->get();


        // Obtener los servicios no eliminados y activos, con sus días y horarios
        $servicios = Servicio::where('eliminado', 1)
            ->where('estado', 1) // 1 = Activo
            ->with(['diasHorarios.diaSemana'])
            ->get();

        return view('admin.inscripciones.create', compact('membresias', 'servicios'));
    }

    // Método para almacenar la inscripción
    public function store(Request $request)
    {
        $request->validate([
            'idCliente' => 'required|exists:clientes,idCliente',
            'productos' => 'required|json',
        ]);

        $productos = json_decode($request->productos, true);

        if (empty($productos)) {
            return redirect()->back()->withErrors(['Debe seleccionar al menos un producto.']);
        }

        DB::beginTransaction();

        try {
            $tieneMembresiaActiva = false;
            $cantidadMembresias = 0;

            foreach ($productos as $productoData) {
                if ($productoData['tipoProducto'] == 'membresia') {
                    $cantidadMembresias++;

                    if ($cantidadMembresias > 1) {
                        return response()->json([
                            'message' => 'Solo puede adquirir una membresía a la vez.'
                        ], 422);
                    }

                    // Verificar si el cliente ya tiene una membresía activa
                    $membresiaActiva = DetalleInscripcion::where('tipoProducto', 'membresia')
                        ->whereHas('inscripcion', function ($query) use ($request) {
                            $query->where('idCliente', $request->idCliente)
                                ->where('estado', 'activa');
                        })
                        ->first();

                    if ($membresiaActiva) {
                        return response()->json([
                            'message' => 'El cliente ya tiene una membresía activa.'
                        ], 422);
                    }

                    $tieneMembresiaActiva = true;
                }
            }

            // Crear la inscripción
            $inscripcion = new Inscripcion();
            $inscripcion->idCliente = $request->idCliente;
            $inscripcion->idUsuario = auth()->user()->idUsuario;
            $inscripcion->totalPago = 0; // Se calculará más adelante
            $inscripcion->estado = 'activa';
            $inscripcion->diasRestantes = null; // Inicialmente null
            $inscripcion->save();

            $totalPago = 0;

            foreach ($productos as $productoData) {
                $detalle = new DetalleInscripcion();
                $detalle->idInscripcion = $inscripcion->idInscripcion;
                $detalle->tipoProducto = $productoData['tipoProducto'];

                if ($productoData['tipoProducto'] == 'membresia') {
                    $membresia = Membresia::find($productoData['idProducto']);
                    $detalle->idMembresia = $productoData['idProducto'];
                    $detalle->precio = $membresia->precio;

                    $fechaInscripcion = $inscripcion->fechaInscripcion;
                    $inscripcion->fechaFin = Carbon::parse($fechaInscripcion)->addDays($membresia->duracionDias);

                    // Aplicar descuento si existe
                    $detalle->descuento = $productoData['descuento'] ?? 0;

                    // Validar descuento
                    if ($detalle->descuento < 0 || $detalle->descuento > $detalle->precio) {
                        throw new \Exception('El descuento es inválido para el producto: ' . $productoData['idProducto']);
                    }

                    // $precioFinal = $detalle->precio - $detalle->descuento;
                    $precioFinal = $detalle->precio - ($detalle->precio * $detalle->descuento / 100);

                    $totalPago += $precioFinal;

                    // Actualizar días restantes en la inscripción
                    $inscripcion->diasRestantes = $membresia->duracionDias;

                    // Guardar el detalle
                    $detalle->save();

                } else if ($productoData['tipoProducto'] == 'servicio') {
                    // Validación de servicio ya inscrito
                    $servicioExistente = DetalleInscripcion::where('tipoProducto', 'servicio')
                        ->where('idServicio', $productoData['idProducto'])
                        ->whereHas('inscripcion', function ($query) use ($request) {
                            $query->where('idCliente', $request->idCliente)
                                ->where('estado', 'activa');
                        })
                        ->first();

                    if ($servicioExistente) {
                        return response()->json([
                            'message' => 'El cliente ya está inscrito en uno de los servicios seleccionados.'
                        ], 422);
                    }

                    $servicio = Servicio::find($productoData['idProducto']);

                    // Verificar si hay capacidad disponible
                    if ($servicio->capacidad <= 0) {
                        return response()->json([
                            'message' => 'El servicio "' . $servicio->nombre . '" no tiene cupos disponibles.'
                        ], 422);
                    }

                    $detalle->idServicio = $productoData['idProducto'];
                    $detalle->precio = $servicio->precioTotal;

                    // Aplicar descuento si existe
                    $detalle->descuento = $productoData['descuento'] ?? 0;

                    // Validar descuento
                    if ($detalle->descuento < 0 || $detalle->descuento > $detalle->precio) {
                        throw new \Exception('El descuento es inválido para el producto: ' . $productoData['idProducto']);
                    }

                    // $precioFinal = $detalle->precio - $detalle->descuento;
                    $precioFinal = $detalle->precio - ($detalle->precio * $detalle->descuento / 100);

                    $totalPago += $precioFinal;

                    // Establecer sesiones restantes si aplica
                    if ($servicio->cantidadSesiones) {
                        $detalle->sesionesRestantes = $servicio->cantidadSesiones;
                    }

                    // Guardar el detalle
                    $detalle->save();

                    // Reducir la capacidad del servicio en 1
                    $servicio->capacidad -= 1;

                    // Verificar que la capacidad no sea negativa
                    if ($servicio->capacidad < 0) {
                        throw new \Exception('La capacidad del servicio "' . $servicio->nombre . '" es insuficiente.');
                    }

                    $servicio->save();
                }
            }

            // Actualizar montos en la inscripción
            $inscripcion->totalPago = $totalPago;
            $inscripcion->save();


            // Obtener los datos necesarios para el comprobante

            // Preparar los datos del comprobante
            $cliente = Cliente::find($request->idCliente);
            $fecha = now()->format('d/m/Y');

            $productosDetalles = [];
            foreach ($productos as $productoData) {
                $precioFinal = $productoData['precio'] - ($productoData['precio'] * ($productoData['descuento'] ?? 0) / 100);
                $productosDetalles[] = [
                    'nombre' => $productoData['nombre'],
                    'tipoProducto' => $productoData['tipoProducto'],
                    'precio' => $productoData['precio'],
                    'descuento' => $productoData['descuento'] ?? 0,
                    'precioFinal' => $precioFinal,
                ];
            }

            $data = [
                'cliente' => [
                    'nombreCompleto' => $cliente->nombre . ' ' . $cliente->primerApellido,
                ],
                'fecha' => $fecha,
                'productos' => $productosDetalles,
                'totalPago' => $totalPago,
            ];

            // Generar un identificador único para el comprobante
            $comprobanteId = uniqid();

            // Almacenar los datos del comprobante en la sesión
            session()->put('comprobantes.' . $comprobanteId, $data);
            DB::commit();
            // Devolver la respuesta JSON
            return response()->json(['comprobanteId' => $comprobanteId]);

            //  return redirect()->route('admin.inscripciones.create')->with('success', 'Inscripción realizada correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['Ocurrió un error al realizar la inscripción: ' . $e->getMessage()]);
        }
    }



    public function generarPDF($id)
    {
        // Recuperar los datos del comprobante
        $data = session()->get('comprobantes.' . $id);

        if (!$data) {
            return redirect()->route('admin.inscripciones.create')->with('error', 'El comprobante no está disponible.');
        }


        $qrData = json_encode([
            'cliente' => $data['cliente'],
            'fecha' => $data['fecha'],
            'productos' => $data['productos'],
            'totalPago' => $data['totalPago'],
        ]);
        // Generar el código QR
        // Generar el código QR
        $qrCode = QrCode::size(150)->generate($qrData);


        $pdf = PDF::loadView('pdf.comprobante2', [
            'data' => $data,
            'qrCode' => base64_encode($qrCode), // Codificar para enviarlo a la vista
        ]);

        // Eliminar los datos del comprobante de la sesión
        session()->forget('comprobantes.' . $id);

        // Mostrar el PDF en el navegador
        return $pdf->stream('comprobante_inscripcion.pdf');
    }

    public function storeCliente(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:50',
            'primerApellido' => 'required|string|max:50',
            'fechaNacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            // No se incluyen campos opcionales
        ]);

        // Crear un nuevo cliente
        $cliente = new Cliente();
        $cliente->nombre = $validatedData['nombre'];
        $cliente->primerApellido = $validatedData['primerApellido'];
        $cliente->fechaNacimiento = $validatedData['fechaNacimiento'];
        $cliente->genero = $validatedData['genero'];
        $cliente->idAutor = auth()->user()->idUsuario ?? null; // Ajusta según tu modelo de usuario
        $cliente->eliminado = 1; // O el valor que corresponda

        // Guardar el cliente
        $cliente->save();

        // Devolver una respuesta JSON para actualizar el select2

        return response()->json([
            'id' => $cliente->idCliente,
            'text' => $cliente->nombre . ' ' . $cliente->primerApellido,
            'message' => '¡Cliente creado exitosamente!',
            'success' => true
        ]);
        /*return response()->json([
             'id' => $cliente->idCliente,
             'text' => $cliente->nombre . ' ' . $cliente->primerApellido,
         ]);*/
    }




    // Método para búsqueda de clientes en tiempo real
    public function searchCliente(Request $request)
    {
        $search = $request->get('term');
        $clientes = Cliente::where('nombre', 'LIKE', '%' . $search . '%')
            ->orWhere('primerApellido', 'LIKE', '%' . $search . '%')
            ->get();

        $result = [];
        foreach ($clientes as $cliente) {
            $result[] = [
                'id' => $cliente->idCliente,
                'text' => $cliente->nombre . ' ' . $cliente->primerApellido
            ];
        }

        return response()->json($result);
    }



    
    /*  
    Lógica para aplicar descuento basado en el código promocional
   
    protected function aplicarDescuento($codigoPromocion, $totalPago)
    {
        $promocion = Promocion::where('codigo', $codigoPromocion)->first();

        if ($promocion) {
            if ($promocion->tipo == 'porcentaje') {
                return $totalPago * ($promocion->valor / 100);
            } elseif ($promocion->tipo == 'fijo') {
                return $promocion->valor;
            }
        }

        return 0; // Si no hay descuento
    }  */



    public function generarComprobante($id)
    {
        // Obtener la inscripción con todas las relaciones necesarias
        $inscripcion = Inscripcion::with(['cliente', 'detallesInscripciones.membresia', 'detallesInscripciones.servicio'])
            ->findOrFail($id);

        // Verificar si el cliente ya tiene un código QR
        $cliente = $inscripcion->cliente;
        if (!$cliente->qrCode) {
            return redirect()->back()->with('error', 'El cliente no tiene un código QR generado.');
        }

        // Obtener la ruta del código QR almacenado
        $qrCodePath = 'storage/' . $cliente->qrCode; // Ruta donde se almacenó el QR en el disco público

        // Obtener el logo del gimnasio
        $logo = public_path('dist/assets/images/logo3.png');  // Ruta del logo

        // Cargar la vista del comprobante y generar el PDF
        $pdf = Pdf::loadView('admin.inscripciones.comprobanteFinal', compact('inscripcion', 'qrCodePath', 'logo'))
            ->setPaper('a4', 'portrait'); // Ajusta el tamaño y la orientación del papel si es necesario

        // Habilitar la carga remota de imágenes
        $pdf->getDomPDF()->set_option("enable_remote", true);
        $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

        // Establecer la ruta base para las imágenes
        $pdf->getDomPDF()->set_option("chroot", public_path());

        // Devolver el PDF para que se abra en una nueva ventana
        return $pdf->stream('comprobante_inscripcion_' . $inscripcion->idInscripcion . '.pdf');
    }


    public function generarComprobanteServicio($idDetalle)
    {
        // Obtener el detalle de inscripción del servicio con las relaciones necesarias
        // Obtener el detalle de inscripción del servicio con las relaciones necesarias, incluyendo los horarios
        $detalleInscripcion = DetalleInscripcion::with(['inscripcion.cliente', 'servicio.diasHorarios.diaSemana'])
            ->findOrFail($idDetalle);


        // Verificar si el detalle tiene un código QR generado
        if (!$detalleInscripcion->qrCode) {
            return redirect()->back()->with('error', 'El servicio no tiene un código QR generado.');
        }

        // Obtener el cliente y las rutas del código QR y logo
        $cliente = $detalleInscripcion->inscripcion->cliente;
        $qrCodePath = 'storage/' . $detalleInscripcion->qrCode;
        $logo = public_path('dist/assets/images/logo3.png');


        $horarios = $detalleInscripcion->servicio->diasHorarios->map(function ($horario) {
            return [
                'dia' => $horario->diaSemana ? ucfirst($horario->diaSemana->nombreDia) : 'N/A',
                'horaInicio' => date('H:i', strtotime($horario->horaInicio)),
                'horaFin' => date('H:i', strtotime($horario->horaFin)),
            ];
        });

        // Generar el PDF
        $pdf = Pdf::loadView('admin.inscripciones.comprobanteServicio', compact('detalleInscripcion', 'cliente', 'qrCodePath', 'logo', 'horarios'))
            ->setPaper('a4', 'portrait');

        // Habilitar opciones de carga remota
        $pdf->getDomPDF()->set_option("enable_remote", true);
        $pdf->getDomPDF()->set_option("isRemoteEnabled", true);
        $pdf->getDomPDF()->set_option("chroot", public_path());

        return $pdf->stream('comprobante_servicio_' . $detalleInscripcion->idDetalle . '.pdf');
    }








    // Método para generar el ticket si el pago fue completado
    public function generarTicket($id)
    {
        $inscripcion = Inscripcion::findOrFail($id);

        if ($inscripcion->estadoPago === 'completado') {
            // Lógica para generar el ticket en PDF o en otra forma
            return response()->json(['status' => 'success', 'message' => 'Ticket generado.']);
        }

        return response()->json(['status' => 'error', 'message' => 'No se puede generar el ticket, el pago está pendiente.']);
    }

    public function obtenerCliente($id)
    {
        $cliente = Cliente::find($id); // Asegúrate de usar el modelo correcto
        return response()->json($cliente);
    }





    public function index(Request $request)
    {
        // Filtros
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');
        $estado = $request->input('estado');

        // Consulta base para membresías
        $queryMembresias = Inscripcion::whereHas('detallesInscripciones', function ($q) {
            $q->where('tipoProducto', 'membresia');
        });

        // Consulta base para servicios
        $queryServicios = Inscripcion::whereHas('detallesInscripciones', function ($q) {
            $q->where('tipoProducto', 'servicio');
        });

        // Aplicar filtros a ambas consultas
        if ($fecha_inicio) {
            $queryMembresias->where('fechaInscripcion', '>=', $fecha_inicio);
            $queryServicios->where('fechaInscripcion', '>=', $fecha_inicio);
        }

        if ($fecha_fin) {
            $queryMembresias->where('fechaInscripcion', '<=', $fecha_fin);
            $queryServicios->where('fechaInscripcion', '<=', $fecha_fin);
        }

        if ($estado) {
            $queryMembresias->where('estado', $estado);
            $queryServicios->where('estado', $estado);
        }

        // Obtener inscripciones con relaciones necesarias para membresías
        $inscripcionesMembresias = $queryMembresias->with(['cliente', 'detallesInscripciones.membresia'])->get();

        // Obtener inscripciones con relaciones necesarias para servicios
        $inscripcionesServicios = $queryServicios->with(['cliente', 'detallesInscripciones.servicio'])->get();

        // Procesar inscripciones de membresías
        foreach ($inscripcionesMembresias as $inscripcion) {
            $detalle = $inscripcion->detallesInscripciones->where('tipoProducto', 'membresia')->first();
            if ($detalle && $detalle->membresia) {
                $inscripcion->producto = $detalle->membresia->nombre;
                $inscripcion->fechaInicio = Carbon::parse($inscripcion->fechaInscripcion);
                $inscripcion->fechaFin = Carbon::parse($inscripcion->fechaInscripcion)->addDays($detalle->membresia->duracionDias);
            } else {
                $inscripcion->producto = 'Membresía no especificada';
                $inscripcion->fechaInicio = null;
                $inscripcion->fechaFin = null;
            }
            $inscripcion->montoPago = $inscripcion->detallesInscripciones->sum('precio');
        }

        // Procesar inscripciones de servicios
        foreach ($inscripcionesServicios as $inscripcion) {
            $detalle = $inscripcion->detallesInscripciones->where('tipoProducto', 'servicio')->first();
            if ($detalle && $detalle->servicio) {
                $inscripcion->producto = $detalle->servicio->nombre;
                $inscripcion->fechaInicio = Carbon::parse($inscripcion->fechaInscripcion);
                $inscripcion->fechaFin = null; // O asigna la fecha si aplica
            } else {
                $inscripcion->producto = 'Servicio no especificado';
                $inscripcion->fechaInicio = null;
                $inscripcion->fechaFin = null;
            }
            $inscripcion->montoPago = $inscripcion->detallesInscripciones->sum('precio');
        }

        // Contadores para las tarjetas (opcional, puedes ajustarlos según tus necesidades)
        $totalMembresias = $inscripcionesMembresias->count();
        $totalServicios = $inscripcionesServicios->count();

        $totalActivas = Inscripcion::where('estado', 'activa')->count();
        $totalVencidas = Inscripcion::where('estado', 'vencida')->count();
        $totalCanceladas = Inscripcion::where('estado', 'cancelada')->count();

        return view('admin.inscripciones.index', compact(
            'inscripcionesMembresias',
            'inscripcionesServicios',
            'fecha_inicio',
            'fecha_fin',
            'estado',
            'totalMembresias',
            'totalServicios',
            'totalActivas',
            'totalVencidas',
            'totalCanceladas'
        ));
    }

    // generar qr 





    public function generarQr($idInscripcion)
    {
        // Obtener la inscripción y el cliente asociado
        $inscripcion = Inscripcion::findOrFail($idInscripcion);
        $cliente = $inscripcion->cliente;

        // Obtener el detalle de la inscripción para obtener el tipo de producto (membresía o servicio)
        $detalleInscripcion = DetalleInscripcion::where('idInscripcion', $idInscripcion)->first();

        if ($detalleInscripcion) {
            // Inicializamos la variable de la fecha de fin
            $fechaFin = '';

            // Verificar si es una membresía
            if ($detalleInscripcion->tipoProducto === 'membresia') {
                // Obtener la membresía asociada y su fecha de fin
                $membresia = Membresia::find($detalleInscripcion->idMembresia);
                if ($membresia) {
                    $fechaFin = $membresia->fechaFin;
                }
            } elseif ($detalleInscripcion->tipoProducto === 'servicio') {
                // Si es un servicio, podrías tomar la fecha de fin del servicio o dejarlo vacío
                $servicio = Servicio::find($detalleInscripcion->idServicio);
                if ($servicio) {
                    $fechaFin = $servicio->fechaFin;
                }
            }

            // Verificar si el cliente ya tiene un código QR generado
            if (!$cliente->qrCode) {
                // Generar los datos para el código QR
                $qrData = "Cliente: {$cliente->nombre} {$cliente->primerApellido} {$cliente->segundoApellido} | 
            Inscripción ID: {$inscripcion->idInscripcion} | 
            Fecha de Fin: {$fechaFin} | 
            Tipo: {$detalleInscripcion->tipoProducto}";

                // Generar el código QR en formato PNG utilizando GD
                $qrCode = QrCode::size(200)->generate($qrData);

                // Generar un nombre de archivo único
                $filePath = 'qrcodes/cliente_' . $cliente->idCliente . '_' . time() . '.svg';

                // Guardar el archivo QR en el almacenamiento público
                Storage::disk('public')->put($filePath, $qrCode);

                // Guardar la ruta del QR en el campo qrCode del cliente
                $cliente->qrCode = $filePath;
                $cliente->save();
            }

            return redirect()->back()->with('success', 'Código QR generado correctamente.');
        } else {
            return redirect()->back()->with('error', 'No se pudo generar el QR, no se encontró el detalle de la inscripción.');
        }
    }


    public function generarQr2($idInscripcion)
    {
        // Obtener la inscripción y el cliente asociado
        $inscripcion = Inscripcion::findOrFail($idInscripcion);
        $cliente = $inscripcion->cliente;

        // Obtener el detalle de la membresía
        $detalleInscripcion = $inscripcion->detalles()->where('tipoProducto', 'membresia')->first();

        if ($detalleInscripcion) {
            // Obtener la membresía asociada y su fecha de fin
            $membresia = Membresia::find($detalleInscripcion->idMembresia);
            if ($membresia) {
                // Asumiendo que tienes un campo 'duracion' en días en la membresía
                $fechaFin = $inscripcion->created_at->addDays($membresia->duracion)->format('d/m/Y');
            } else {
                $fechaFin = '';
            }

            // Generar los datos para el código QR
            $qrData = "Cliente: {$cliente->nombre} {$cliente->primerApellido} {$cliente->segundoApellido} | 
Inscripción ID: {$inscripcion->idInscripcion} | 
Fecha de Fin: {$fechaFin} | 
Tipo: Membresía";

            // Generar el código QR en formato SVG
            $qrCode = QrCode::format('svg')->size(200)->generate($qrData);

            // Generar un nombre de archivo único
            $filePath = 'qrcodes/cliente_' . $cliente->idCliente . '_' . time() . '.svg';

            // Guardar el archivo QR en el almacenamiento público
            Storage::disk('public')->put($filePath, $qrCode);

            // Guardar la ruta del QR en el campo qrCode del cliente
            $cliente->qrCode = $filePath;
            $cliente->save();

            return true;
        } else {
            return false;
        }
    }


    public function marcarComoVencida($id)
    {
        $inscripcion = Inscripcion::findOrFail($id);

        // Verificar que la inscripción sea una membresía
        $esMembresia = $inscripcion->detallesInscripciones->contains(function ($detalle) {
            return $detalle->tipoProducto === 'membresia';
        });

        if (!$esMembresia) {
            return response()->json(['error' => 'La inscripción no es una membresía.'], 400);
        }

        // Verificar si la fecha actual supera la fechaFin
        $hoy = Carbon::today();
        if ($inscripcion->fechaFin && $inscripcion->fechaFin->gt($hoy)) {
            return response()->json(['error' => 'La membresía aún no ha vencido.'], 400);
        }

        // Actualizar el estado a 'vencida'
        $inscripcion->estado = 'vencida';
        $inscripcion->save();

        // Eliminar el código QR asociado al cliente
        $cliente = $inscripcion->cliente;
        if ($cliente->qrCode) {
            // Eliminar el archivo físico
            Storage::disk('public')->delete($cliente->qrCode);

            // Actualizar el campo qrCode a null
            $cliente->qrCode = null;
            $cliente->save();
        }

        return response()->json(['success' => 'La membresía ha sido marcada como vencida y el QR ha sido eliminado.']);
    }


    public function generarQrParaMembresia($idCliente)
    {
        // Obtener el cliente
        $cliente = Cliente::findOrFail($idCliente);

        if ($cliente->qrCode) {
            return redirect()->back()->with('error', 'Este cliente ya tiene un código QR generado.');
        }
        $membresiaActiva = Inscripcion::where('idCliente', $idCliente)
            ->where('estado', 'activa')
            ->whereHas('detallesInscripciones', function ($query) {
                $query->where('tipoProducto', 'membresia');
            })
            ->first();

        if (!$membresiaActiva) {
            return redirect()->back()->with('error', 'El cliente no tiene una membresía activa.');
        }

        $qrData = "{$cliente->idCliente},membresia";
        // Datos únicos para el QR
        /*$qrData = "Cliente: {$cliente->nombre} {$cliente->primerApellido}\n" .
            "ID Cliente: {$cliente->idCliente}\n" .
            "Días Restantes: {$membresiaActiva->diasRestantes}\n" .
            "Tipo: Membresía";*/

        // Generar el QR en formato SVG
        $svgQr = QrCode::format('svg')
            ->size(200)   // Ajustar el tamaño según sea necesario
            ->margin(2)   // Añadir un margen
            ->generate($qrData);

        // Definir la ruta de almacenamiento
        $filePath = "qrcodes/membresias/cliente_{$cliente->idCliente}.svg";

        // Guardar el SVG en el almacenamiento público
        Storage::disk('public')->put($filePath, $svgQr);

        // Guardar la ruta en el cliente
        $cliente->qrCode = $filePath;
        $cliente->save();

        return redirect()->back()->with('success', 'Código QR generado y almacenado correctamente.');
    }
    public function generarQrParaServicio($idDetalleInscripcion)
    {
        // Obtener el detalle de inscripción del servicio
        $detalleInscripcion = DetalleInscripcion::findOrFail($idDetalleInscripcion);

        if ($detalleInscripcion->qrCode) {
            return redirect()->back()->with('error', 'Este servicio ya tiene un código QR generado.');
        }

        // Verificar que el detalle de inscripción es de tipo servicio
        if ($detalleInscripcion->tipoProducto !== 'servicio') {
            return redirect()->back()->with('error', 'El QR solo puede generarse para inscripciones de servicio.');
        }

        // Obtener el cliente asociado y las sesiones restantes del servicio
        $cliente = $detalleInscripcion->inscripcion->cliente;

        // Datos únicos para el QR de servicio
        $qrData = "{$cliente->idCliente},servicio,{$detalleInscripcion->idDetalle}";

        // Definir la ruta de almacenamiento para el QR
        $filePath = "qrcodes/servicios/detalle_{$detalleInscripcion->idDetalle}.svg";

        // Generar el QR en formato SVG y guardarlo
        $svgQr = QrCode::format('svg')
            ->size(200)
            ->margin(2)
            ->generate($qrData);

        Storage::disk('public')->put($filePath, $svgQr);

        // Guardar la ruta del QR en el detalle de inscripción del servicio
        $detalleInscripcion->qrCode = $filePath;
        $detalleInscripcion->save();

        return redirect()->back()->with('success', 'Código QR de servicio generado correctamente.');
    }









    public function cancelar(Request $request, $id)
    {
        // Obtener la inscripción con sus detalles
        $inscripcion = Inscripcion::with('cliente', 'detallesInscripciones')->findOrFail($id);

        // Cambiar el estado de la inscripción a 'cancelada'
        $inscripcion->update(['estado' => 'cancelada']);

        // Verificar si la membresía de este cliente tiene un código QR y eliminarlo
        if ($inscripcion->cliente && $inscripcion->cliente->qrCode) {
            $qrPath = public_path('storage/qrCodes/membresias/' . $inscripcion->cliente->qrCode);
            if (File::exists($qrPath)) {
                File::delete($qrPath); // Eliminar el archivo QR de la membresía del cliente
            }

            // Remover el código QR de la base de datos (columna `qrCode` en `clientes`)
            $inscripcion->cliente->update(['qrCode' => null]);
        }

        // Recorrer cada detalle de la inscripción para gestionar los QR de los servicios
        foreach ($inscripcion->detallesInscripciones as $detalle) {
            if ($detalle->tipoProducto === 'servicio' && $detalle->qrCode) {
                // Eliminar el QR del servicio
                $qrPath = public_path('storage/qrCodes/servicios/' . $detalle->qrCode);
                if (File::exists($qrPath)) {
                    File::delete($qrPath);
                }

                // Actualizar el estado del detalle a 'cancelada'
                $detalle->update(['estado' => 'cancelada']);
            }
        }

        return response()->json(['success' => 'Venta anulada y códigos QR eliminados correctamente.']);
    }


    public function detalleServicios($id)
    {
        $inscripcion = Inscripcion::with([
            'detallesInscripciones' => function ($query) {
                $query->where('tipoProducto', 'servicio')
                    ->select('idDetalle', 'idInscripcion', 'idServicio', 'qrCode', 'precio')
                    ->with('servicio:idServicio,nombre');
            }
        ])
            ->where('idInscripcion', $id)
            ->firstOrFail(['idInscripcion']);

        $detallesServicios = $inscripcion->detallesInscripciones->map(function ($detalle) {
            return [
                'idDetalle' => $detalle->idDetalle,
                'nombre' => $detalle->servicio ? $detalle->servicio->nombre : 'Servicio no disponible',
                'qrCode' => $detalle->qrCode,
                'precio' => $detalle->precio,
            ];
        });

        return response()->json(['detallesServicios' => $detallesServicios]);
    }



    public function generarCredencial($id)
    {
        $inscripcion = Inscripcion::with(['detalleInscripciones.membresia'])->findOrFail($id);

        // Verificar si es membresía y está activa
        $detalleMembresia = $inscripcion->detalleInscripciones->where('tipoProducto', 'membresia')->first();

        if ($detalleMembresia && $inscripcion->estado == 'activa') {
            // Lógica para generar credencial con QR
            return response()->download('path/to/credencial.pdf');
        } else {
            return redirect()->back()->with('error', 'No es una membresía activa.');
        }
    }

    public function enviarWhatsapp($id)
    {
        $inscripcion = Inscripcion::with(['detalleInscripciones.membresia', 'cliente'])->findOrFail($id);

        // Verificar si es membresía
        $detalleMembresia = $inscripcion->detalleInscripciones->where('tipoProducto', 'membresia')->first();

        if ($detalleMembresia) {
            // Lógica para enviar QR por WhatsApp
            return redirect()->back()->with('success', 'QR enviado por WhatsApp.');
        } else {
            return redirect()->back()->with('error', 'No es una membresía.');
        }
    }

    public function generarPase($id)
    {
        $inscripcion = Inscripcion::with(['detalleInscripciones.servicio'])->findOrFail($id);

        // Verificar si es servicio
        $detalleServicio = $inscripcion->detalleInscripciones->where('tipoProducto', 'servicio')->first();

        if ($detalleServicio) {
            // Lógica para generar pase de entrada
            return response()->download('path/to/pase_entrada.pdf');
        } else {
            return redirect()->back()->with('error', 'No es un servicio.');
        }
    }
    public function updateEstado(Request $request, $id)
    {
        $inscripcion = Inscripcion::findOrFail($id);
        $inscripcion->estado = $request->input('estado');
        $inscripcion->save();

        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }

    /*  public function updateEstadoPago(Request $request, $id)
      {
          $inscripcion = Inscripcion::findOrFail($id);
          $inscripcion->estadoPago = $request->input('estadoPago');
          $inscripcion->save();

          return redirect()->back()->with('success', 'Estado de pago actualizado correctamente.');
      }*/

    // Actualizar el estado de la inscripción
    /* public function updateEstado(Request $request, $id)
     {
         $inscripcion = Inscripcion::findOrFail($id);
         $inscripcion->estado = $request->input('estado');
         $inscripcion->save();

         return redirect()->route('admin.inscripciones.index')->with('success', 'El estado de la inscripción se ha actualizado correctamente.');
     }*/
    /*
    // Actualizar el estado de pago de la inscripción
    public function updateEstadoPago(Request $request, $id)
    {
        $inscripcion = Inscripcion::findOrFail($id);
        $inscripcion->estadoPago = $request->input('estadoPago');
        $inscripcion->save();

        return redirect()->route('admin.inscripciones.index')->with('success', 'El estado de pago se ha actualizado correctamente.');
    }
*/
    // Eliminar inscripción
    public function destroy($id)
    {
        $inscripcion = Inscripcion::findOrFail($id);
        $inscripcion->delete();

        return redirect()->route('admin.inscripciones.index')->with('success', 'La inscripción ha sido eliminada correctamente.');
    }

}
