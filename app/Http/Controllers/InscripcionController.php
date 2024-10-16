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
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
class InscripcionController extends Controller
{


    public function create()
    {
        $clientes = Cliente::all();
        $membresias = Membresia::all();
        $secciones = Seccion::all();
        $servicios = Servicio::all();
        return view('admin.inscripciones.create', compact('clientes', 'membresias', 'secciones', 'servicios'));
    }

    public function store(Request $request)
    {
        \Log::info($request->all());

        // Validaciones
        $request->validate([
            'idCliente' => 'required|exists:clientes,idCliente',
            'tipoProducto' => 'required|in:membresia,servicio',
            'idMembresia' => 'nullable|required_if:tipoProducto,membresia|exists:membresias,idMembresia',
            'idSeccion' => 'nullable|required_if:tipoProducto,servicio|exists:secciones,idSeccion',
            'cantidadSecciones' => 'nullable|required_if:tipoProducto,servicio|integer|min:1',
            'totalPago' => 'required|numeric|min:0',
            'accion' => 'nullable|in:nueva,renovar,actualizar' // Nueva acción: renovar o actualizar membresía
        ]);

        // Iniciar una transacción para asegurar que todos los datos se registren correctamente
        DB::beginTransaction();

        try {
            // Verificar si el cliente ya tiene una membresía activa
            $membresiaActiva = Inscripcion::where('idCliente', $request->idCliente)
                ->where('estado', 'activa')
                ->whereHas('detalleInscripciones', function ($query) {
                    $query->where('tipoProducto', 'membresia');
                })
                ->first();

            // Inicializar total de pago
            $totalPago = 0;

            // Procesar la lógica de membresía
            if ($request->tipoProducto == 'membresia') {
                $membresia = Membresia::find($request->idMembresia);
                $totalPago = $membresia->precio;
            }

            // Procesar la lógica de servicios
            if ($request->tipoProducto == 'servicio') {
                $seccion = Seccion::find($request->idSeccion);

                // Validar la capacidad disponible de la sección
                if ($seccion->capacidad < $request->cantidadSecciones) {
                    return redirect()->back()->with('error', 'No hay suficiente capacidad en esta sección.');
                }

                // Calcular el precio del servicio
                $totalPago = $seccion->precioPorSeccion * $request->cantidadSecciones;

                // Si el servicio incluye el precio de entrada y el cliente NO tiene una membresía activa, sumar el costo de entrada
                if ($seccion->servicio->incluyeCostoEntrada && !$membresiaActiva) {
                    $precioEntrada = 10.00; // Suponiendo un precio fijo para la entrada
                    $totalPago += $precioEntrada;
                }
            }

            // Crear la inscripción
            $inscripcion = Inscripcion::create([
                'idCliente' => $request->idCliente,
                'idUsuario' => auth()->id(),
                'totalPago' => $totalPago,
                'diasRestantes' => $request->tipoProducto == 'membresia' ? $membresia->duracionDias : null,
            ]);

            // Crear el detalle de la inscripción
            DetalleInscripcion::create([
                'idInscripcion' => $inscripcion->idInscripcion,
                'tipoProducto' => $request->tipoProducto,
                'idMembresia' => $request->tipoProducto == 'membresia' ? $request->idMembresia : null,
                'idSeccion' => $request->tipoProducto == 'servicio' ? $request->idSeccion : null,
                'precio' => $totalPago,
                'cantidadSecciones' => $request->tipoProducto == 'servicio' ? $request->cantidadSecciones : 1, // Para servicios, de lo contrario se establece en 1
            ]);

            // Si es un servicio, reducir la capacidad de la sección seleccionada
            if ($request->tipoProducto == 'servicio') {
                $seccion->capacidad -= $request->cantidadSecciones;
                $seccion->save();
            }

            DB::commit();

            return redirect()->route('admin.inscripciones.index')
                ->with('success', 'Inscripción creada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ocurrió un error al crear la inscripción: ' . $e->getMessage());
        }
    }



    /**
     * Lógica para aplicar descuento basado en el código promocional
   
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
    


    public function detalle($id)
    {
        $inscripcion = Inscripcion::with(['cliente', 'detalleInscripciones.membresia', 'detalleInscripciones.servicio'])->findOrFail($id);

        // Devolver los datos en formato JSON para el modal
        return response()->json($inscripcion);
    }

    public function cancelar(Request $request, $id)
    {
        $inscripcion = Inscripcion::findOrFail($id);
        $inscripcion->estado = 'cancelada';
        $inscripcion->save();

        return redirect()->back()->with('success', 'La venta ha sido anulada.');
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
