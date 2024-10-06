<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Asistencia;
use App\Models\Cliente;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;



class AsistenciaController extends Controller
{
    public function asistencias(Request $request)
    {
        // Obtener el cliente autenticado (suponiendo que hay un sistema de autenticación)
        $clienteId = auth()->user()->cliente->idCliente;

        // Filtros de fecha (si no se seleccionan, se muestran los últimos 30 días por defecto)
        $fechaInicio = $request->input('fechaInicio', Carbon::now()->subDays(30)->format('Y-m-d'));
        $fechaFin = $request->input('fechaFin', Carbon::now()->format('Y-m-d'));

        // Obtener asistencias del cliente en el rango de fechas
        $asistencias = Asistencia::where('idCliente', $clienteId)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'asc')
            ->get();

        // Para el gráfico: obtener las fechas y frecuencias de asistencias
        $fechas = $asistencias->pluck('fecha')->unique();
        $frecuenciaAsistencias = $fechas->map(function ($fecha) use ($asistencias) {
            return $asistencias->where('fecha', $fecha)->count();
        });

        // Comprobar ausencias prolongadas
        $diasSinAsistir = Carbon::now()->diffInDays(Carbon::parse($asistencias->last()->fecha ?? Carbon::now()));

        return view('cliente.asistencias.asistencia', compact('asistencias', 'fechas', 'frecuenciaAsistencias', 'diasSinAsistir'));
    }



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
        // Obtener los clientes para el filtro
        $clientes = Cliente::all();

        // Filtros de cliente y fechas
        $query = Asistencia::query();

        // Filtrar por cliente si se selecciona
        if ($request->filled('idCliente')) {
            $query->where('idCliente
            ', $request->cliente_id);
        }

        // Filtrar por fechas si se seleccionan
        if ($request->filled('fechaInicio')) {
            $query->whereDate('fecha', '>=', $request->fechaInicio);
        }

        if ($request->filled('fechaFin')) {
            $query->whereDate('fecha', '<=', $request->fechaFin);
        }

        // Obtener las asistencias
        $asistencias = $query->with('cliente')->orderBy('fecha', 'desc')->get();

        // Estadísticas para las tarjetas
        $totalAsistencias = $asistencias->count();
        $asistenciasHoy = $asistencias->where('fecha', Carbon::now()->format('Y-m-d'))->count();
        $asistenciasSinHoraSalida = $asistencias->whereNull('horaSalida')->count();

        return view('admin.asistencias.index', [
            'asistencias' => $asistencias,
            'clientes' => $clientes,
            'totalAsistencias' => $totalAsistencias,
            'asistenciasHoy' => $asistenciasHoy,
            'asistenciasSinHoraSalida' => $asistenciasSinHoraSalida,
            'fechaInicio' => $request->fechaInicio,
            'fechaFin' => $request->fechaFin,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validar los datos enviados en el modal
        $validated = $request->validate([
            'horaEntrada' => 'required|date_format:H:i',
            'horaSalida' => 'nullable|date_format:H:i|after_or_equal:horaEntrada', // La hora de salida debe ser posterior a la hora de entrada
        ]);

        // Encontrar la asistencia
        $asistencia = Asistencia::findOrFail($id);

        // Actualizar los datos de la asistencia
        $asistencia->horaEntrada = $request->horaEntrada;
        $asistencia->horaSalida = $request->horaSalida;
        $asistencia->save();

        // Redireccionar con un mensaje de éxito
        return redirect()->route('admin.asistencias.index')
            ->with('success', 'Asistencia actualizada correctamente.');
    }


    public function exportarPDF(Request $request)
    {
        $asistencias = $this->getAsistenciasFiltradas($request);

        // Pasar el objeto request a la vista
        $pdf = PDF::loadView('admin.asistencias.asistencias-pdf', [
            'asistencias' => $asistencias,
            'request' => $request
        ]);

        // Descargar el PDF
        return $pdf->download('reporte_asistencias.pdf');
    }



    // Función para obtener las asistencias filtradas (usada en PDF/Excel)
    private function getAsistenciasFiltradas(Request $request)
    {
        $clienteId = $request->input('idCliente');
        $fechaInicio = $request->input('fechaInicio', Carbon::now()->subMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fechaFin', Carbon::now()->format('Y-m-d'));

        return Asistencia::with('cliente')
            ->when($clienteId, function ($query) use ($clienteId) {
                return $query->where('idCliente', $clienteId);
            })
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'asc')
            ->get();
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
            return redirect()->back()->with('error', 'El usuario ingresado no existe.')->withInput();
        }

        // Verificar si el usuario es un cliente
        $cliente = Cliente::where('idUsuario', $usuario->idUsuario)->first();
        if (!$cliente) {
            return redirect()->back()->with('error', 'El usuario ingresado no es un cliente.')->withInput();
        }

        $hoy = Carbon::now('America/La_Paz')->format('Y-m-d');
        $asistencia = Asistencia::where('idCliente', $cliente->idCliente)
            ->whereDate('fecha', $hoy)
            ->first();

        if ($request->accion == 'entrada') {
            // Verifica si ya tiene entrada registrada hoy
            if ($asistencia && $asistencia->horaEntrada) {
                return redirect()->back()->with('error', 'Ya se ha registrado una entrada para este cliente hoy.');
            }

            // Si no hay asistencia registrada hoy, registrar la entrada
            if (!$asistencia) {
                Asistencia::create([
                    'idCliente' => $cliente->idCliente,
                    'fecha' => $hoy,
                    'horaEntrada' => Carbon::now('America/La_Paz')->toTimeString(),
                    'idAutor' => auth()->id(),
                    'eliminado' => 1,
                ]);
            } else {
                // Si ya hay un registro de asistencia pero sin entrada, actualiza con la hora de entrada
                $asistencia->update([
                    'horaEntrada' => Carbon::now('America/La_Paz')->toTimeString(),
                    'idAutor' => auth()->id(),
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

            // Registrar la hora de salida
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
        $term = $request->input('term');
        $clientes = Cliente::join('usuarios', 'clientes.idUsuario', '=', 'usuarios.idUsuario')
            ->where(function ($query) use ($term) {
                $query->where('usuarios.nombreUsuario', 'LIKE', "%$term%")
                    ->orWhere('usuarios.nombre', 'LIKE', "%$term%")
                    ->orWhere('usuarios.primerApellido', 'LIKE', "%$term%");
            })
            ->select('usuarios.idUsuario', 'usuarios.nombreUsuario', 'usuarios.nombre', 'usuarios.primerApellido')
            ->limit(10)
            ->get();

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

    public function destroy($id)
    {
        $asistencia = Asistencia::findOrFail($id);
        $asistencia->update(['eliminado' => 0]); // Marcando como eliminado, según tu lógica
        return back()->with('success', 'Asistencia eliminada correctamente.');
    }


    public function mostrarAsistencias()
    {
        // Obtener asistencias del cliente autenticado en formato Y-m-d
        $asistencias = Asistencia::where('idCliente', auth()->user()->id)
            ->pluck('fecha') // Asegúrate de que la columna se llame 'fecha'
            ->map(function ($fecha) {
                return \Carbon\Carbon::parse($fecha)->format('Y-m-d');
            })
            ->toArray();

        return view('cliente.asistencias.view', compact('asistencias'));
    }


}
