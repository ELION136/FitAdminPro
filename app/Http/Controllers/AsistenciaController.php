<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Asistencia;
use App\Models\Cliente;
use App\Models\Inscripcion;
use App\Models\User;
use Carbon\Carbon;
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
        $dataQR = $request->input('dataQR');  // La respuesta del QR

        // Aquí extraemos los datos basados en el QR
        $data = explode('|', $dataQR);  // Separar el contenido del QR
        $idCliente = trim(explode(':', $data[0])[1]);
        $idInscripcion = trim(explode(':', $data[1])[1]);

        // Verificar si existe la inscripción y está activa
        $inscripcion = Inscripcion::where('idInscripcion', $idInscripcion)
            ->where('idCliente', $idCliente)
            ->first();

        if ($inscripcion && $inscripcion->estado === 'activa') {
            // Verificar si ya existe una asistencia para el cliente en la fecha actual
            $asistenciaExistente = Asistencia::where('idCliente', $idCliente)
                ->where('idInscripcion', $idInscripcion)
                ->whereDate('fechaAsistencia', now()->toDateString())  // Comparar por la fecha actual
                ->first();

            if ($asistenciaExistente) {
                return response()->json(['error' => 'Ya has registrado tu asistencia para el día de hoy.']);
            }

            // Registrar la nueva asistencia
            Asistencia::create([
                'idCliente' => $idCliente,
                'idInscripcion' => $idInscripcion,
                'metodoRegistro' => 'QR',
            ]);

            return response()->json(['success' => 'Asistencia registrada correctamente']);
        }

        return response()->json(['error' => 'No se pudo registrar la asistencia. Inscripción no válida.']);
    }




    // Función para ver las asistencias
    public function verAsistencias(Request $request)
{
    // Obtener todos los clientes para el filtro
    $clientes = Cliente::all();

    // Filtros
    $clienteId = $request->input('cliente_id');
    $fechaInicio = $request->input('fechaInicio');
    $fechaFin = $request->input('fechaFin');

    // Query base de asistencias
    $query = Asistencia::where('eliminado', 1); // Solo mostrar asistencias con eliminado = 1

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

    // Obtener las asistencias filtradas
    $asistencias = $query->with('cliente')->orderBy('fechaAsistencia', 'desc')->get();

    // Estadísticas: Total de asistencias, asistencias del día, y sin hora de salida
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


    public function registrarAsistenciaQR(Request $request)
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
