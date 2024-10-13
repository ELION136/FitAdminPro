<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membresia;
use App\Models\Cliente;
use App\Models\Inscripcion;
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

        $planes = Membresia::where('eliminado', 1)->get();
        $clientes = Cliente::where('eliminado', 1)->get();

        return view('admin.inscripciones.create', compact('planes', 'clientes'));
    }

    private function generatePassword()
    {
        $prefix = 'fitadminpro';
        $randomNumbers = rand(1000, 9999);
        return $prefix . $randomNumbers;
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,idCliente',
            'membresia_id' => 'required|exists:membresias,idMembresia',
            'fecha_inicio' => 'required|date|after_or_equal:today',
        ]);

        if (!$request->cliente_id) {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'primerApellido' => 'required|string|max:255',
                'segundoApellido' => 'nullable|string|max:255',
                'fechaNacimiento' => 'required|date',
                'genero' => 'required|string|in:Masculino,Femenino,Otro',
                'email' => 'required|string|email|max:255|unique:usuarios,email',
                'nombreUsuario' => 'required|string|max:255|unique:usuarios,nombreUsuario',
            ]);
        }

        $membresia = Membresia::find($request->membresia_id);

        if ($request->monto_pago != $membresia->precio) {
            return redirect()->back()->with('error', 'El monto del pago no coincide con el precio del plan seleccionado.');
        }

        // Variable para almacenar el cliente y la inscripción
        $cliente = null;
        $inscripcion = null;

        DB::transaction(function () use ($request, $membresia, &$cliente, &$inscripcion) {
            $nuevoCliente = false;
            $temporaryPassword = null;

            if (!$request->filled('cliente_id')) {
                // Generar contraseña automáticamente
                $temporaryPassword = $this->generatePassword();

                // Crear un nuevo usuario
                $usuario = User::create([
                    'nombreUsuario' => $request->nombreUsuario,
                    'email' => $request->email,
                    'password' => Hash::make($temporaryPassword),
                    'rol' => 'Cliente',
                    'idAutor' => auth()->user()->idUsuario,
                ]);

                // Crear un nuevo cliente
                $cliente = Cliente::create([
                    'idUsuario' => $usuario->idUsuario,
                    'nombre' => $request->nombre,
                    'primerApellido' => $request->primerApellido,
                    'segundoApellido' => $request->filled('segundoApellido') ? $request->segundoApellido : '',
                    'fechaNacimiento' => $request->fechaNacimiento,
                    'genero' => $request->genero,
                    'direccion' => $request->direccion,
                    'idAutor' => auth()->user()->idUsuario,
                ]);

                $nuevoCliente = true;
            } else {
                $cliente = Cliente::find($request->cliente_id);
            }

            // Crear la inscripción
            $inscripcion = Inscripcion::create([
                'idCliente' => $cliente->idCliente,
                'idMembresia' => $request->membresia_id,
                'fechaInicio' => $request->fecha_inicio,
                'fechaFin' => Carbon::parse($request->fecha_inicio)->addDays($membresia->duracion),
                'estado' => 'activa',
                'montoPago' => $request->monto_pago,
                'fechaPago' => now(),
                'estadoPago' => $request->estado_pago,
                'idAutor' => auth()->user()->idUsuario,
            ]);

            // Enviar correo si se creó un nuevo cliente (opcional)
            // if ($nuevoCliente) {
            //     Mail::to($usuario->email)->send(new \App\Mail\TemporaryMemberPasswordMail($usuario, $temporaryPassword, $inscripcion));
            // }
        });

        // Fuera de la transacción, generar y descargar el PDF
        if ($cliente && $inscripcion) {
            // Generar el PDF como comprobante
            $pdf = Pdf::loadView('pdf.comprobante', [
                'cliente' => $cliente,
                'membresia' => $membresia,
                'montoPago' => $request->monto_pago,
                'fechaInicio' => $request->fecha_inicio,
                'fechaFin' => Carbon::parse($request->fecha_inicio)->addDays($membresia->duracion),
                'fechaPago' => now(),
            ]);

            // Descargar el PDF (puedes cambiar a `stream` si prefieres que se muestre directamente en el navegador)
            return $pdf->download('comprobante_pago.pdf');
        }

        return back()->with('success', 'Inscripción registrada exitosamente.');
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
        $queryMembresias = Inscripcion::whereHas('detalleInscripciones', function ($q) {
            $q->where('tipoProducto', 'membresia');
        });

        // Consulta base para servicios
        $queryServicios = Inscripcion::whereHas('detalleInscripciones', function ($q) {
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

        // Obtener inscripciones con relaciones necesarias
        $inscripcionesMembresias = $queryMembresias->with(['cliente', 'detalleInscripciones.membresia'])->get();
        $inscripcionesServicios = $queryServicios->with(['cliente', 'detalleInscripciones.servicio'])->get();

        // Procesar inscripciones de membresías
        foreach ($inscripcionesMembresias as $inscripcion) {
            $detalle = $inscripcion->detalleInscripciones->where('tipoProducto', 'membresia')->first();
            if ($detalle && $detalle->membresia) {
                $inscripcion->producto = $detalle->membresia->nombre;
                $inscripcion->fechaInicio = Carbon::parse($inscripcion->fechaInscripcion);
                $inscripcion->fechaFin = Carbon::parse($inscripcion->fechaInscripcion)->addDays($detalle->membresia->duracionDias);
            } else {
                $inscripcion->producto = 'Membresía no especificada';
                $inscripcion->fechaInicio = null;
                $inscripcion->fechaFin = null;
            }
            $inscripcion->montoPago = $inscripcion->detalleInscripciones->sum('precio');
        }

        // Procesar inscripciones de servicios
        foreach ($inscripcionesServicios as $inscripcion) {
            $detalle = $inscripcion->detalleInscripciones->where('tipoProducto', 'servicio')->first();
            if ($detalle && $detalle->servicio) {
                $inscripcion->producto = $detalle->servicio->nombre;
                $inscripcion->fechaInicio = Carbon::parse($inscripcion->fechaInscripcion);
                $inscripcion->fechaFin = null; // O asigna la fecha si aplica
            } else {
                $inscripcion->producto = 'Servicio no especificado';
                $inscripcion->fechaInicio = null;
                $inscripcion->fechaFin = null;
            }
            $inscripcion->montoPago = $inscripcion->detalleInscripciones->sum('precio');
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
