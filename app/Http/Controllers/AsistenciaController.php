<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Asistencia;
use App\Models\Cliente;
use App\Models\User;
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


            $asistencias = $query->orderBy('fecha', 'desc')
            ->orderBy('horaEntrada', 'desc')
            ->paginate(15);
        
        $usuarios = User::where('eliminado', 1)->get(); // Obtener usuarios activos
        return view('admin.asistencias.index', compact('usuarios', 'asistencias'));
    }



    public function registro(Request $request)
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

        //return view('admin.asistencias.index', compact('asistencias'));
        $fecha = Carbon::now()->toDateString();
        $hora = Carbon::now()->toTimeString();
        $usuarios = User::where('eliminado', 1)->get(); // Obtener usuarios activos
        return view('admin.asistencias.registrar', compact('fecha', 'hora', 'usuarios', 'asistencias'));
    }
    public function registrar(Request $request)
    {
        $request->validate([
            'nombreUsuario' => 'required|string',
            'accion' => 'required|in:entrada,salida',
        ]);

        // Buscar el usuario por nombreUsuario
        $usuario = User::where('nombreUsuario', $request->nombreUsuario)->first();

        if (!$usuario) {
            return redirect()->back()->with('error', 'El usuario ingresado no existe.');
        }

        // Verificar si el usuario es un cliente
        $cliente = Cliente::where('idUsuario', $usuario->idUsuario)->first();
        if (!$cliente) {
            return redirect()->back()->with('error', 'El usuario ingresado no es un cliente.');
        }

        $hoy = Carbon::now()->format('Y-m-d');
        $asistencia = Asistencia::where('idCliente', $cliente->idCliente)
            ->whereDate('fecha', $hoy)
            ->first();

        if ($request->accion == 'entrada') {
            if ($asistencia && $asistencia->horaEntrada) {
                return redirect()->back()->with('error', 'Ya se ha registrado una entrada para este cliente hoy.');
            }

            if ($asistencia) {
                $asistencia->update([
                    'horaEntrada' => Carbon::now('America/La_Paz')->toTimeString(),
                    'idAutor' => auth()->id(),
                ]);
            } else {
                Asistencia::create([
                    'idCliente' => $cliente->idCliente,
                    'fecha' => $hoy,
                    'horaEntrada' => Carbon::now('America/La_Paz')->toTimeString(),
                    'idAutor' => auth()->id(),
                    'eliminado' => 1,
                ]);
            }

            return back()->with('success', 'Entrada registrada exitosamente.');
        }

        if ($request->accion == 'salida') {
            if (!$asistencia || !$asistencia->horaEntrada) {
                return back()->with('error', 'No se puede registrar la salida sin una entrada previa.');
            }

            if ($asistencia->horaSalida) {
                return back()->with('error', 'Ya se ha registrado una salida para este cliente hoy.');
            }

            $asistencia->update([
                'horaSalida' => Carbon::now('America/La_Paz')->toTimeString(),
                'idAutor' => auth()->id(),
            ]);

            return back()->with('success', 'Salida registrada exitosamente.');
        }

        return back()->with('error', 'Acción no válida.');
    }
    public function autocompleteClientes(Request $request)
    {
        $term = $request->get('term');

        $clientes = Cliente::whereHas('usuario', function ($query) use ($term) {
            $query->where('nombreUsuario', 'LIKE', '%' . $term . '%')
                ->where('eliminado', 1);
        })->with('usuario')
            ->get()
            ->map(function ($cliente) {
                return [
                    'value' => $cliente->usuario->nombreUsuario,
                    'label' => $cliente->usuario->nombreUsuario . ' - ' . $cliente->nombre
                ];
            });

        return response()->json($clientes);
    }

    public function estadisticas(Request $request)
    {
        // Obtener las fechas del filtro, o establecer valores predeterminados
        $fechaInicio = $request->input('fecha_inicio', now()->subMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->format('Y-m-d'));

        // Consulta de asistencias totales
        $totalAsistencias = Asistencia::where('eliminado', 1)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->count();

        // Asistencias por día en el rango de fechas
        $asistenciasPorDia = Asistencia::where('eliminado', 1)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->select(DB::raw('DATE(fecha) as dia'), DB::raw('count(*) as total'))
            ->groupBy('dia')
            ->orderBy('dia', 'desc')
            ->take(7)
            ->get();

        // Clientes más frecuentes en el rango de fechas
        $clientesMasFrecuentes = Cliente::withCount([
            'asistencia' => function ($query) use ($fechaInicio, $fechaFin) {
                $query->where('eliminado', 1)
                    ->whereBetween('fecha', [$fechaInicio, $fechaFin]);
            }
        ])
            ->orderBy('asistencia_count', 'desc')
            ->take(5)
            ->get();

        // Asistencias detalladas por cliente en el rango de fechas
        $asistenciasFiltradas = Asistencia::where('eliminado', 1)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->with('cliente') // Asumiendo que existe una relación 'cliente' en el modelo Asistencia
            ->get();

        return view('admin.asistencias.estadisticas', compact('totalAsistencias', 'asistenciasPorDia', 'clientesMasFrecuentes', 'asistenciasFiltradas', 'fechaInicio', 'fechaFin'));
    }




    public function edit($id)
    {
        $asistencia = Asistencia::findOrFail($id);
        return view('admin.asistencias.edit', compact('asistencia'));
    }

    public function update(Request $request, $id)
    {
        $asistencia = Asistencia::findOrFail($id);

        // Validación de los datos ingresados
        $request->validate([
            'fecha' => 'required|date',
            'hora_entrada' => 'required|date_format:H:i',
            'hora_salida' => 'nullable|date_format:H:i',
            // Agregar aquí más reglas de validación según los campos de la tabla Asistencia
        ]);

        // Actualización de los datos de la asistencia
        $asistencia->fecha = $request->input('fecha');
        $asistencia->hora_entrada = $request->input('hora_entrada');
        $asistencia->hora_salida = $request->input('hora_salida');
        // Guardar otros campos si es necesario

        $asistencia->save();

        // Redireccionar con un mensaje de éxito
        return redirect()->route('admin.asistencias.index')->with('success', 'Asistencia actualizada correctamente.');
    }
    public function destroy($id)
    {
        $asistencia = Asistencia::findOrFail($id);
        $asistencia->update(['eliminado' => 0]); // Marcando como eliminado, según tu lógica
        return back()->with('success', 'Asistencia eliminada correctamente.');
    }


}
