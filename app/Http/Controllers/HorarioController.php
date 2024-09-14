<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Servicio;
use App\Models\ServicioHorario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Entrenador;

class HorarioController extends Controller
{
    // Mostrar los horarios con entrenadores
    public function index()
    {
        $horarios = Horario::with('entrenador')->where('eliminado', 1)->get();
        $entrenadores = Entrenador::all();
        return view('admin.horarios.index', compact('horarios', 'entrenadores'));
    }

    // Crear horario
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idEntrenador' => 'required|exists:entrenadores,idEntrenador',
            'dia' => 'required|string',
            'horaInicio' => 'required|date_format:H:i',
            'horaFin' => 'required|date_format:H:i|after:horaInicio',
            'capacidad' => 'required|integer|min:1',
        ]);

        // Añadir el id del usuario logueado como autor
        $validated['idAutor'] = Auth::id(); // id del usuario logueado

        Horario::create($validated);

        return redirect()->route('admin.horarios.index')->with('success', 'Horario creado con éxito.');
    }

    // Editar horario
    public function update(Request $request, Horario $horario)
    {
        $validated = $request->validate([
            'idEntrenador' => 'required|exists:entrenadores,idEntrenador',
            'dia' => 'required|string',
            'horaInicio' => 'required|date_format:H:i',
            'horaFin' => 'required|date_format:H:i|after:horaInicio',
            'capacidad' => 'required|integer|min:1',
        ]);

        // Actualizar el id del usuario logueado como autor de la modificación
        $validated['idAutor'] = Auth::id();

        $horario->update($validated);

        return redirect()->route('admin.horarios.index')->with('success', 'Horario actualizado con éxito.');
    }

    // Eliminar horario
    public function destroy(Horario $horario)
    {
        // Marcar como eliminado y añadir el id del usuario que realizó la acción
        $horario->update([
            'eliminado' => 0,
            'idAutor' => Auth::id()
        ]);

        return redirect()->route('admin.horarios.index')->with('success', 'Horario eliminado con éxito.');
    }

    
    
}
