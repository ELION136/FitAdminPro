<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;

class ServiciosController extends Controller
{
    public function index()
    {
        $servicios = Servicio::where('eliminado', 1)->get();
        return view('admin.servicios.index', compact('servicios'));
    }

    // Muestra el formulario para crear un nuevo servicio
    public function create()
    {
        return view('admin.servicios.create');
    }

    // Almacena un nuevo servicio en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'duracion' => 'required|integer',
            'tipoServicio' => 'required|in:Individual,Grupal,Online,Presencial',
            'categoria' => 'required|in:Entrenamiento,Nutrición,Rehabilitación,Otro',
        ]);

        Servicio::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'duracion' => $request->duracion,
            'tipoServicio' => $request->tipoServicio,
            'categoria' => $request->categoria,
            'idAutor' => auth()->user()->idUsuario, // Suponiendo que estás guardando el ID del autor
            'eliminado' => 1,
        ]);

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio creado exitosamente.');
    }

    // Muestra un servicio específico
    public function show(Servicio $servicio)
    {
        return view('admin.servicios.show', compact('servicio'));
    }

    // Muestra el formulario para editar un servicio existente
    public function edit(Servicio $servicio)
    {
        return view('admin.servicios.edit', compact('servicio'));
    }

    // Actualiza un servicio existente en la base de datos
    public function update(Request $request, Servicio $servicio)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'duracion' => 'required|integer',
            'tipoServicio' => 'required|in:Individual,Grupal,Online,Presencial',
            'categoria' => 'required|in:Entrenamiento,Nutrición,Rehabilitación,Otro',
        ]);

        $servicio->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'duracion' => $request->duracion,
            'tipoServicio' => $request->tipoServicio,
            'categoria' => $request->categoria,
            'idAutor' => auth()->user()->idUsuario, // Actualizando el autor de la modificación
        ]);

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio actualizado exitosamente.');
    }

    // Elimina (lógicamente) un servicio
    public function destroy(Servicio $servicio)
    {
        $servicio->update(['eliminado' => 0]);

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio eliminado exitosamente.');
    }
}
