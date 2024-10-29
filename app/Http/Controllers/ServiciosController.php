<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use Carbon\Carbon;
use App\Models\DiaSemana;
use App\Models\Entrenador;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class ServiciosController extends Controller
{
    public function index()
    {
        $servicios = Servicio::where('eliminado', 1)->with('diasSemana')->get();
        $entrenadores = Entrenador::where('eliminado', 1)->get();

        return view('admin.servicios.index', compact('servicios', 'entrenadores'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'capacidad' => 'required|integer|min:1',
            'precioTotal' => 'required|numeric|min:0|max:10000',
            'cantidadSesiones' => 'nullable|integer|min:1',
            'idEntrenador' => 'required|exists:entrenadores,idEntrenador'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $servicio = Servicio::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'capacidad' => $request->capacidad,
            'precioTotal' => $request->precioTotal,
            'cantidadSesiones' => $request->cantidadSesiones,
            'idEntrenador' => $request->idEntrenador,
            'estado' => 0, // Inicialmente inactivo
            'idAutor' => Auth::id(),
            'eliminado' => 1
        ]);

        return response()->json(['success' => 'Servicio creado exitosamente, asigna horarios para activarlo.']);
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'capacidad' => 'required|integer|min:1',
            'precioTotal' => 'required|numeric|min:0|max:10000',
            'cantidadSesiones' => 'nullable|integer|min:1',
            'idEntrenador' => 'required|exists:entrenadores,idEntrenador'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $servicio->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'capacidad' => $request->capacidad,
            'precioTotal' => $request->precioTotal,
            'cantidadSesiones' => $request->cantidadSesiones,
            'idEntrenador' => $request->idEntrenador,
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
