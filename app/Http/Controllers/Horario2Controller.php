<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Entrenador;
use App\Models\Servicio;

class Horario2Controller extends Controller
{
    public function index()
    {
        $horarios = Horario::with(['entrenador', 'servicio'])->get();
        return view('admin.horarios2.index', compact('horarios'));
    }

    public function create()
    {
        $entrenadores = Entrenador::all();
        $servicios = Servicio::all();
        return view('admin.horarios2.create', compact('entrenadores', 'servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idEntrenador' => 'required|exists:entrenadores,idEntrenador',
            'dia' => 'required|string|max:20',
            'horaInicio' => 'required|date_format:H:i',
            'horaFin' => 'required|date_format:H:i|after:horaInicio',
            'capacidad' => 'required|integer|min:1',
        ]);

        Horario::create($request->all());
        return redirect()->route('admin.horarios2.index')->with('success', 'Horario creado exitosamente');
    }

    public function edit($id)
    {
        $horario = Horario::findOrFail($id);
        $entrenadores = Entrenador::all();
        $servicios = Servicio::all();
        return view('admin.horarios2.edit', compact('horario', 'entrenadores', 'servicios'));
    }

    public function update(Request $request, $id)
    {
        $horario = Horario::findOrFail($id);

        $request->validate([
            'idEntrenador' => 'required|exists:entrenadores,idEntrenador',
            'dia' => 'required|string|max:20',
            'horaInicio' => 'required|date_format:H:i',
            'horaFin' => 'required|date_format:H:i|after:horaInicio',
            'capacidad' => 'required|integer|min:1',
        ]);

        $horario->update($request->all());
        return redirect()->route('admin.horarios2.index')->with('success', 'Horario actualizado exitosamente');
    }

    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        $horario->delete();
        return redirect()->route('admin.horarios2.index')->with('success', 'Horario eliminado exitosamente');
    }
}
