<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Servicio;
use App\Models\ServicioHorario;
use Carbon\Carbon;
use App\Models\Entrenador;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Mostrar la vista del calendario con los horarios del entrenador
    public function index()
    {
        // Obtener todos los entrenadores y servicios
        $entrenadores = Entrenador::all();
        $servicios = Servicio::all();

        // Retornar la vista con los datos necesarios
        return view('admin.horarios.index', compact('entrenadores', 'servicios'));
    }



    public function getHorarios($idEntrenador)
    {
        // Obtener los horarios del entrenador seleccionado
        $horarios = Horario::where('idEntrenador', $idEntrenador)->get();

        if ($horarios->isEmpty()) {
            return response()->json(['message' => 'No se encontraron horarios'], 404);
        }

        return response()->json($horarios);
    }
    public function asignarServicio(Request $request)
    {
        // Validar la solicitud
        $validatedData = $request->validate([
            'idHorario' => 'required|exists:horarios,idHorario',
            'idServicio' => 'required|exists:servicios,idServicio',
        ]);

        // Asignar el servicio al horario
        $servicioHorario = new ServicioHorario();
        $servicioHorario->idHorario = $request->idHorario;
        $servicioHorario->idServicio = $request->idServicio;
        $servicioHorario->estado = 'Activo'; // o según la lógica de negocio
        $servicioHorario->idAutor = auth()->user()->idUsuario;
        $servicioHorario->save();

        return redirect()->back()->with('success', 'Servicio asignado correctamente.');
    }
}
