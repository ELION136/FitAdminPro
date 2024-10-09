<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class ServiciosController extends Controller
{
    public function index()
    {
        $servicios = Servicio::where('eliminado', 1)->get();
        return view('admin.servicios.index', compact('servicios'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'capacidadMaxima' => 'required|integer|min:1',
            'precioPorSeccion' => 'required|numeric|min:0|max:10000',
            'incluyeCostoEntrada' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Servicio::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'capacidadMaxima' => $request->capacidadMaxima,
            'precioPorSeccion' => $request->precioPorSeccion,
            'incluyeCostoEntrada' => $request->incluyeCostoEntrada ? 1 : 0,
            'idAutor' => Auth::id(),
        ]);

        return response()->json(['success' => 'Servicio creado exitosamente.']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'capacidadMaxima' => 'required|integer|min:1',
            'precioPorSeccion' => 'required|numeric|min:0|max:10000',
            'incluyeCostoEntrada' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $servicio = Servicio::findOrFail($id);
        $servicio->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'capacidadMaxima' => $request->capacidadMaxima,
            'precioPorSeccion' => $request->precioPorSeccion,
            'incluyeCostoEntrada' => $request->incluyeCostoEntrada ? 1 : 0,
            'idAutor' => Auth::id(),
        ]);

        return response()->json(['success' => 'Servicio actualizado exitosamente.']);
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->update(['eliminado' => 0]);

        return response()->json(['success' => 'Servicio eliminado exitosamente.']);
    }
}
