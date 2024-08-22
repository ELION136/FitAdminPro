<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Asistencia;
use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class AsistenciaController extends Controller
{
    public function show()
    {
        $usuario = Auth::user();
        $cliente = Cliente::where('idUsuario', $usuario->idUsuario)->first();

        if (!$cliente) {
            return redirect()->back()->with('error', 'No se encontró el perfil de cliente asociado.');
        }

        $ultimasAsistencias = Asistencia::where('idCliente', $cliente->idCliente)
            ->where('eliminado', 1)
            ->orderBy('fecha', 'desc')
            ->orderBy('horaEntrada', 'desc')
            ->take(10)
            ->get();

        $asistenciasMes = Asistencia::where('idCliente', $cliente->idCliente)
            ->where('eliminado', 1)
            ->whereMonth('fecha', now()->month)
            ->count();

        $tiempoTotalMes = Asistencia::where('idCliente', $cliente->idCliente)
            ->where('eliminado', 1)
            ->whereMonth('fecha', now()->month)
            ->whereNotNull('horaSalida')
            ->sum(\DB::raw('TIMESTAMPDIFF(HOUR, horaEntrada, horaSalida)'));

        return view('cliente.asistencias.asistencia', compact('ultimasAsistencias', 'asistenciasMes', 'tiempoTotalMes'));

    }

    public function registrar(Request $request)
    {
        $usuario = Auth::user();
        $cliente = Cliente::where('idUsuario', $usuario->idUsuario)->first();

        if (!$cliente) {
            return redirect()->back()->with('error', 'No se encontró el perfil de cliente asociado.');
        }

        $ultimaAsistencia = Asistencia::where('idCliente', $cliente->idCliente)
            ->where('fecha', today())
            ->whereNull('horaSalida')
            ->where('eliminado', 1)
            ->first();

        if ($ultimaAsistencia) {
            // Registrar salida
            $ultimaAsistencia->update([
                'horaSalida' => now()->toTimeString(),
                'idAutor' => $usuario->idUsuario
            ]);
            $mensaje = 'Salida registrada correctamente.';
        } else {
            // Registrar entrada
            Asistencia::create([
                'idCliente' => $cliente->idCliente,
                'fecha' => today(),
                'horaEntrada' => now()->toTimeString(),
                'idAutor' => $usuario->idUsuario,
                'eliminado' => 1
            ]);
            $mensaje = 'Entrada registrada correctamente.';
        }

        return redirect()->route('cliente.asistencias.asistencia')->with('message', $mensaje);

    }

    public function reporte(Request $request)
    {
        // Obtener mes y año de la solicitud o utilizar el mes y año actuales
        $mes = $request->input('mes', date('n'));
        $anio = $request->input('anio', date('Y'));

        // Obtener el usuario autenticado
        $usuario = Auth::user();

        // Verificar si el usuario está autenticado
        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Debes estar autenticado para ver este reporte.');
        }

        // Obtener el cliente asociado al usuario autenticado
        $cliente = Cliente::where('idUsuario', $usuario->idUsuario)->first();
        if (!$cliente) {
            return redirect()->back()->with('error', 'Cliente no encontrado.');
        }

        // Obtener las asistencias del cliente para el mes y año especificados
        $asistencias = Asistencia::where('idCliente', $cliente->idCliente)
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->where('eliminado', 1) // Filtrar por registros activos
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        // Calcular el total de asistencias
        $totalAsistencias = $asistencias->total();

        // Calcular el tiempo total de asistencia para el mes
        $tiempoTotal = Asistencia::where('idCliente', $cliente->idCliente)
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->where('eliminado', 1)
            ->select(DB::raw('SUM(TIMESTAMPDIFF(HOUR, horaEntrada, horaSalida)) as totalHoras'))
            ->value('totalHoras');

        // Calcular el promedio diario de asistencia
        $promedioDiario = $totalAsistencias > 0 ? $tiempoTotal / $totalAsistencias : 0;

        // Calcular la cantidad de asistencias y el tiempo total en el mes
        $asistenciasMes = $asistencias->count();
        $tiempoTotalMes = $tiempoTotal;

        // Obtener las últimas asistencias (por ejemplo, las últimas 5)
        $ultimasAsistencias = $asistencias->take(5);

        // Pasar datos a la vista
        return view('cliente.asistencias.reporte-asistencia', compact(
            'asistencias',
            'mes',
            'anio',
            'totalAsistencias',
            'tiempoTotal',
            'promedioDiario',
            'asistenciasMes',
            'tiempoTotalMes',
            'ultimasAsistencias'
        ));
    }

    //para el panel del admin y el panel de usuaario

    public function index(Request $request)
    {
        $query = Asistencia::with('cliente')
            ->where('eliminado', 1);

        // Filtros
        if ($request->has('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        if ($request->has('cliente')) {
            $query->whereHas('cliente', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->cliente . '%');
            });
        }

        $asistencias = $query->orderBy('fecha', 'desc')
            ->orderBy('horaEntrada', 'desc')
            ->paginate(15);

        return view('admin.asistencias.index', compact('asistencias'));
    }

    public function registrarManualmente(Request $request)
    {
        $request->validate([
            'idCliente' => 'required|exists:clientes,idCliente',
            'fecha' => 'required|date',
            'horaEntrada' => 'required|date_format:H:i',
            'horaSalida' => 'nullable|date_format:H:i|after:horaEntrada',
        ]);

        Asistencia::create([
            'idCliente' => $request->idCliente,
            'fecha' => $request->fecha,
            'horaEntrada' => $request->horaEntrada,
            'horaSalida' => $request->horaSalida,
            'idAutor' => auth()->id(),
            'eliminado' => 1
        ]);

        return redirect()->back()->with('success', 'Asistencia registrada manualmente.');
    }

    public function estadisticas()
    {
        $totalAsistencias = Asistencia::where('eliminado', 1)->count();
        $asistenciasPorDia = Asistencia::where('eliminado', 1)
            ->select(DB::raw('DATE(fecha) as dia'), DB::raw('count(*) as total'))
            ->groupBy('dia')
            ->orderBy('dia', 'desc')
            ->take(7)
            ->get();

        $clientesMasFrecuentes = Cliente::withCount(['asistencia' => function ($query) {
                $query->where('eliminado', 1);
            }])
            ->orderBy('asistencia_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.asistencias.estadisticas', compact('totalAsistencias', 'asistenciasPorDia', 'clientesMasFrecuentes'));
    }



    //en considedacion
    /*
    public function corregirAsistencia(Request $request, Asistencia $asistencia)
    {
        $this->authorize('update', $asistencia);

        $request->validate([
            'horaEntrada' => 'required|date_format:H:i',
            'horaSalida' => 'nullable|date_format:H:i|after:horaEntrada',
        ]);

        $horaEntrada = Carbon::createFromFormat('Y-m-d H:i', $asistencia->fecha->format('Y-m-d') . ' ' . $request->horaEntrada);
        $horaSalida = $request->horaSalida ? Carbon::createFromFormat('Y-m-d H:i', $asistencia->fecha->format('Y-m-d') . ' ' . $request->horaSalida) : null;

        $duracion = $horaSalida ? $horaSalida->diffInHours($horaEntrada) : null;

        $asistencia->update([
            'horaEntrada' => $horaEntrada,
            'horaSalida' => $horaSalida,
            'duracion' => $duracion,
        ]);

        return redirect()->back()->with('message', 'Asistencia corregida correctamente.');
    }

    public function eliminarAsistencia(Asistencia $asistencia)
    {
        $this->authorize('delete', $asistencia);

        $asistencia->delete();

        return redirect()->back()->with('message', 'Asistencia eliminada correctamente.');
    }*/
}
