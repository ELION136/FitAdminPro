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
    // Muestra la vista de asistencias con el formulario de búsqueda
    public function index()
    {
        return view('admin.asistencias.index');
    }

    // Búsqueda en tiempo real de cliente
    public function buscarCliente(Request $request)
    {
        if ($request->has('id')) {
            $cliente = Cliente::with([
                'inscripciones' => function ($query) {
                    $query->where('estado', 'activa');
                },
                'inscripciones.detalleInscripciones' => function ($query) {
                    $query->where('tipoProducto', 'membresia');
                },
                'inscripciones.detalleInscripciones.membresia'
            ])->find($request->input('id'));

            return response()->json($cliente);
        } else {
            $clientes = Cliente::where('nombre', 'LIKE', '%' . $request->query('q') . '%')
                ->orWhere('primerApellido', 'LIKE', '%' . $request->query('q') . '%')
                ->get();

            return response()->json($clientes);
        }
    }


    // Registra la asistencia manualmente
    public function registrarAsistencia(Request $request)
    {
        $clienteId = $request->input('idCliente');
        $inscripcionId = $request->input('idInscripcion');
        $autorId = auth()->user()->id;

        // Verificar si el cliente ya tiene una asistencia registrada hoy
        $asistenciaHoy = Asistencia::where('idCliente', $clienteId)
            ->whereDate('fechaAsistencia', Carbon::today())
            ->exists();

        if (!$asistenciaHoy) {
            // Registrar la asistencia
            Asistencia::create([
                'idCliente' => $clienteId,
                'idInscripcion' => $inscripcionId,
                'fechaAsistencia' => now(),
                'metodoRegistro' => 'manual',
                'idAutor' => $autorId,
            ]);

            // Descontar un día en la inscripción
            $inscripcion = Inscripcion::find($inscripcionId);
            $inscripcion->diasRestantes -= 1;
            $inscripcion->save();

            return redirect()->back()->with('success', 'Asistencia registrada exitosamente');
        }

        return redirect()->back()->with('error', 'El cliente ya tiene una asistencia registrada hoy');
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
        $query = Asistencia::query();

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
        $asistenciasHoy = Asistencia::whereDate('fechaAsistencia', Carbon::today())->count();
        

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

}
