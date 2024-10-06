<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;

class ServiciosController extends Controller
{
    public function index()
    {
        // Mostrar solo los servicios activos (eliminado = 1)
        $servicios = Servicio::where('eliminado', 1)->get();
        return view('admin.servicios.index', compact('servicios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable',
            'duracion' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0', // Validación del precio
            'fechaInicio' => 'required|date', // Validación de la fecha de inicio
            'fechaFin' => 'required|date|after_or_equal:fechaInicio', // Validación de la fecha de fin
        ]);

        $servicio = new Servicio($request->all());
        $servicio->idAutor = auth()->id();
        $servicio->eliminado = 1; // Asegura que se marque como activo
        $servicio->save();

        return redirect()->route('admin.servicios.index')->with('success', 'El servicio ha sido creado exitosamente');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idServicio)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable',
            'duracion' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0', // Validación del precio
            'fechaInicio' => 'required|date', // Validación de la fecha de inicio
            'fechaFin' => 'required|date|after_or_equal:fechaInicio', // Validación de la fecha de fin
        ]);

        $servicio = Servicio::findOrFail($idServicio);
        $servicio->update($request->all());
        $servicio->idAutor = auth()->id();
        $servicio->save();

        return redirect()->route('admin.servicios.index')->with('success', 'El servicio ha sido actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idServicio)
    {
        $servicio = Servicio::findOrFail($idServicio);
        $servicio->eliminado = 0; // Eliminación lógica
        $servicio->save();

        return redirect()->route('admin.servicios.index')->with('success', 'El servicio ha sido eliminado exitosamente');
    }
}
