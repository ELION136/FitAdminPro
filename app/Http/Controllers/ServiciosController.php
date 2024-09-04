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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'required',
            'duracion' => 'required|integer|min:1',
            'tipoServicio' => 'required|in:Individual,Grupal,Online,Presencial',
            'categoria' => 'required|in:Entrenamiento,Nutrición,Rehabilitación,Otro',
        ]);

        $servicio = new Servicio($request->all());
        $servicio->idAutor = auth()->id();
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
            'descripcion' => 'required',
            'duracion' => 'required|integer|min:1',
            'tipoServicio' => 'required|in:Individual,Grupal,Online,Presencial',
            'categoria' => 'required|in:Entrenamiento,Nutrición,Rehabilitación,Otro',
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
        $servicio->eliminado = 0;  // Eliminación lógica
        $servicio->save();

        return redirect()->route('admin.servicios.index')->with('success', 'El servicio ha sido eliminado exitosamente');
    }
}
