<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Asistencia;
use App\Models\Cliente;
use App\Models\Inscripcion;
use App\Models\User;
use Carbon\Carbon;
use App\Models\DetalleInscripcion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;



class AsistenciaController extends Controller
{
    public function index()
    {
        return view('admin.asistencias.index');
    }

    // Procesa el código QR y registra la asistencia
    public function registrarQR(Request $request)
    {
        $idCliente = $request->input('idCliente');
        $tipoProducto = $request->input('tipoProducto');
        $idDetalle = $request->input('idDetalle');

        $cliente = Cliente::findOrFail($idCliente);

        if ($tipoProducto === 'membresia') {
            // Verificar si el cliente tiene una membresía activa
            $membresiaActiva = Inscripcion::where('idCliente', $idCliente)
                ->where('estado', 'activa')
                ->whereNotNull('diasRestantes')
                ->first();

            if ($membresiaActiva && $membresiaActiva->diasRestantes > 0) {
                // Registrar asistencia y descontar un día
                $membresiaActiva->decrement('diasRestantes');
            
                $membresiaActiva->save();

                // Cambiar el estado a "vencida" si los días restantes son 0
                if ($membresiaActiva->diasRestantes === 0) {
                    $membresiaActiva->estado = 'vencida';
                    $membresiaActiva->save();
                }

                // Crear registro de asistencia
                Asistencia::create([
                    'idCliente' => $idCliente,
                    'idInscripcion' => $membresiaActiva->idInscripcion,
                    'metodoRegistro' => 'QR',
                    'estado' => 'activa'
                ]);

                return response()->json(['success' => 'Asistencia registrada para membresía.']);
            } else {
                return response()->json(['error' => 'La membresía no está activa o no tiene días restantes.'], 400);
            }
        } elseif ($tipoProducto === 'servicio') {
            // Obtener el detalle de inscripción del servicio
            $detalleInscripcion = DetalleInscripcion::findOrFail($idDetalle);

            // Verificar que el detalle de inscripción es de tipo servicio y tiene sesiones restantes
            if ($detalleInscripcion->tipoProducto !== 'servicio' || $detalleInscripcion->sesionesRestantes <= 0) {
                return response()->json(['error' => 'El servicio no está activo o no tiene sesiones restantes.'], 400);
            }

            // Obtener el día de la semana y hora actual
            $now = Carbon::now();
            $diaSemana = $now->dayOfWeekIso; // 1 (lunes) a 7 (domingo)
            $horaActual = $now->format('H:i:s');

            // Obtener los horarios programados para ese servicio en el día actual
            $horariosServicio = DB::table('servicio_dias_horarios')
                ->where('idServicio', $detalleInscripcion->idServicio)
                ->where('idDia', $diaSemana)
                ->get();

            // Verificar si la hora actual está dentro de alguno de los rangos de horario
            $asistenciaPermitida = false;

            foreach ($horariosServicio as $horario) {
                if ($horaActual >= $horario->horaInicio && $horaActual <= $horario->horaFin) {
                    $asistenciaPermitida = true;
                    break;
                }
            }

            if (!$asistenciaPermitida) {
                return response()->json(['error' => 'No puedes registrar asistencia en este momento el servicio tiene un dia y hora especifico.'], 400);
            }

            // Registrar asistencia y descontar una sesión
            $detalleInscripcion->decrement('sesionesRestantes');

            // Actualizar el modelo después de la operación
            $detalleInscripcion->refresh();

            // Cambiar el estado a "vencida" si las sesiones restantes son 0
            if ($detalleInscripcion->sesionesRestantes === 0) {
                $detalleInscripcion->estado = 'vencida';
                $detalleInscripcion->save();
            }

            // Crear registro de asistencia
            Asistencia::create([
                'idCliente' => $idCliente,
                'idInscripcion' => $detalleInscripcion->idInscripcion,
                'metodoRegistro' => 'QR',
                'estado' => 'activa'
            ]);

            return response()->json(['success' => 'Asistencia registrada para servicio.']);
        } else {
            return response()->json(['error' => 'Tipo de producto inválido.'], 400);
        }
    }





    public function registrarQR1(Request $request)
    {
        
        $idCliente = $request->input('idCliente');
        $tipoProducto = $request->input('tipoProducto');
        $idDetalle = $request->input('idDetalle');

        $cliente = Cliente::findOrFail($idCliente);

        if ($tipoProducto === 'membresia') {
            // Verificar si el cliente tiene una membresía activa
            $membresiaActiva = Inscripcion::where('idCliente', $idCliente)
                ->where('estado', 'activa')
                ->whereNotNull('diasRestantes')
                ->first();

            if ($membresiaActiva && $membresiaActiva->diasRestantes > 0) {
                // Registrar asistencia y descontar un día
                $membresiaActiva->decrement('diasRestantes');
            
                $membresiaActiva->save();

                // Cambiar el estado a "vencida" si los días restantes son 0
                if ($membresiaActiva->diasRestantes === 0) {
                    $membresiaActiva->estado = 'vencida';
                    $membresiaActiva->save();
                }

                // Crear registro de asistencia
                Asistencia::create([
                    'idCliente' => $idCliente,
                    'idInscripcion' => $membresiaActiva->idInscripcion,
                    'metodoRegistro' => 'QR',
                    'estado' => 'activa'
                ]);

                return response()->json(['success' => 'Asistencia registrada para membresía.']);
            } else {
                return response()->json(['error' => 'La membresía no está activa o no tiene días restantes.'], 400);
            }
        } elseif ($tipoProducto === 'servicio') {
            // Obtener el detalle de inscripción del servicio
            $detalleInscripcion = DetalleInscripcion::findOrFail($idDetalle);

            // Verificar que el detalle de inscripción es de tipo servicio y tiene sesiones restantes
            if ($detalleInscripcion->tipoProducto !== 'servicio' || $detalleInscripcion->sesionesRestantes <= 0) {
                return response()->json(['error' => 'El servicio no está activo o no tiene sesiones restantes.'], 400);
            }

            // Obtener el día de la semana y hora actual
            $now = Carbon::now();
            $diaSemana = $now->dayOfWeekIso; // 1 (lunes) a 7 (domingo)
            $horaActual = $now->format('H:i:s');

            // Obtener los horarios programados para ese servicio en el día actual
            $horariosServicio = DB::table('servicio_dias_horarios')
                ->where('idServicio', $detalleInscripcion->idServicio)
                ->where('idDia', $diaSemana)
                ->get();

            // Verificar si la hora actual está dentro de alguno de los rangos de horario
            $asistenciaPermitida = false;

            foreach ($horariosServicio as $horario) {
                if ($horaActual >= $horario->horaInicio && $horaActual <= $horario->horaFin) {
                    $asistenciaPermitida = true;
                    break;
                }
            }

            if (!$asistenciaPermitida) {
                return response()->json(['error' => 'No puedes registrar asistencia en este momento el servicio tiene un dia y hora especifico.'], 400);
            }

            // Registrar asistencia y descontar una sesión
            $detalleInscripcion->decrement('sesionesRestantes');

            // Actualizar el modelo después de la operación
            $detalleInscripcion->refresh();

            // Cambiar el estado a "vencida" si las sesiones restantes son 0
            if ($detalleInscripcion->sesionesRestantes === 0) {
                $detalleInscripcion->estado = 'vencida';
                $detalleInscripcion->save();
            }

            // Crear registro de asistencia
            Asistencia::create([
                'idCliente' => $idCliente,
                'idInscripcion' => $detalleInscripcion->idInscripcion,
                'metodoRegistro' => 'QR',
                'estado' => 'activa'
            ]);

            return response()->json(['success' => 'Asistencia registrada para servicio.']);
        } else {
            return response()->json(['error' => 'Tipo de producto inválido.'], 400);
        }
    }





    // Función para ver las asistencias
    public function verAsistencias(Request $request)
{
    // Obtener todos los clientes para el filtro
    $clientes = Cliente::all();

    // Filtros con fecha actual como valor predeterminado
    $clienteId = $request->input('cliente_id');
    $fechaInicio = $request->input('fechaInicio', Carbon::today()->toDateString());
    $fechaFin = $request->input('fechaFin', Carbon::today()->toDateString());

    // Query base de asistencias
    $query = Asistencia::where('eliminado', 1);

    // Filtrar por cliente si se seleccionó
    if ($clienteId) {
        $query->where('idCliente', $clienteId);
    }

    // Filtrar por fecha de inicio y fin
    if ($fechaInicio) {
        $query->whereDate('fechaAsistencia', '>=', $fechaInicio);
    }

    if ($fechaFin) {
        $query->whereDate('fechaAsistencia', '<=', $fechaFin);
    }

    // Obtener las asistencias filtradas con las relaciones necesarias
    $asistencias = $query->with([
        'cliente',
        'inscripcion.detallesInscripciones.membresia',
        'inscripcion.detallesInscripciones.servicio',
    ])->orderBy('fechaAsistencia', 'desc')->get();

    // Estadísticas
    $totalAsistencias = $asistencias->count();
    $asistenciasHoy = Asistencia::whereDate('fechaAsistencia', Carbon::today())
        ->where('eliminado', 1)
        ->count();

    return view('admin.asistencias.ver', [
        'clientes' => $clientes,
        'asistencias' => $asistencias,
        'totalAsistencias' => $totalAsistencias,
        'asistenciasHoy' => $asistenciasHoy,
        'fechaInicio' => $fechaInicio,
        'fechaFin' => $fechaFin
    ]);
}



    // AsistenciaController.php

    public function anularAsistencia($idAsistencia)
    {
        $asistencia = Asistencia::find($idAsistencia);

        if ($asistencia) {
            $asistencia->eliminado = 0;  // Actualizar el campo eliminado a 0 (anular)
            $asistencia->save();

            return redirect()->route('admin.asistencias.ver')->with('success', 'Asistencia anulada correctamente.');
        }

        return redirect()->route('admin.asistencias.ver')->with('error', 'Asistencia no encontrada.');
    }

    public function verDetalles($idAsistencia)
    {
        $asistencia = Asistencia::with('cliente', 'inscripcion')->find($idAsistencia);

        if (!$asistencia) {
            return redirect()->route('admin.asistencias.index')->with('error', 'Asistencia no encontrada.');
        }

        return view('admin.asistencias.detalles', compact('asistencia'));
    }






    public function qrRegister()
    {
        return view('admin.asistencias.qr-register');
    }


    public function registrarAsistenciaQR4(Request $request)
    {
        // Decodificar los datos del QR que incluye el ID del cliente
        $qrData = $request->input('qrData'); // El QR contiene los datos de cliente e inscripción

        // Parsear los datos obtenidos del QR
        $clienteId = $qrData['idCliente']; // Extraer ID del cliente
        $inscripcionId = $qrData['idInscripcion']; // Extraer ID de la inscripción

        // Verificar que el cliente existe y tiene una inscripción activa
        $inscripcion = Inscripcion::where('idInscripcion', $inscripcionId)
            ->where('idCliente', $clienteId)
            ->where('estado', 'activa')
            ->first();

        if ($inscripcion) {
            // Verificar si el cliente ya registró su asistencia hoy
            $hoy = Carbon::now()->toDateString();
            $asistenciaHoy = Asistencia::where('idCliente', $clienteId)
                ->whereDate('fechaAsistencia', $hoy)
                ->first();

            if ($asistenciaHoy) {
                return response()->json(['error' => 'Asistencia ya registrada para hoy'], 400);
            }

            // Registrar la asistencia si es válida
            $asistencia = new Asistencia();
            $asistencia->idCliente = $clienteId;
            $asistencia->idInscripcion = $inscripcionId;
            $asistencia->fechaAsistencia = now();
            $asistencia->metodoRegistro = 'QR';
            $asistencia->estado = 'activa';
            $asistencia->save();

            return response()->json(['success' => 'Asistencia registrada correctamente'], 200);
        }

        return response()->json(['error' => 'Cliente o inscripción inválida'], 404);
    }
}
