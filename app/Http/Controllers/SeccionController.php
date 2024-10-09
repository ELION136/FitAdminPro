<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Seccion;
use App\Models\Servicio;
use App\Models\Entrenador;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SeccionController extends Controller
{
    public function index()
    {
        $secciones = Seccion::where('eliminado', 1)->get();
        $servicios = Servicio::where('eliminado', 1)->get();
        $entrenadores = Entrenador::where('eliminado', 1)->get();
        return view('admin.secciones.index', compact('secciones', 'servicios', 'entrenadores'));
    }

    public function store(Request $request)
    {
        try {
            // Validaciones y lógica del controlador
            $validator = Validator::make($request->all(), [
                'idServicio' => 'required|exists:servicios,idServicio',
                'fechaInicio' => 'required|date|before_or_equal:fechaFin',
                'fechaFin' => 'required|date|after_or_equal:fechaInicio',
                'horaInicio' => 'required|date_format:H:i|before:horaFin',
                'horaFin' => 'required|date_format:H:i|after:horaInicio',
                'capacidad' => 'required|integer|min:1|max:' . $request->capacidadMaxima,
                'idEntrenador' => 'nullable|exists:entrenadores,idEntrenador',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
    
            // Creación de la sección
            Seccion::create([
                'idServicio' => $request->idServicio,
                'fechaInicio' => $request->fechaInicio,
                'fechaFin' => $request->fechaFin,
                'horaInicio' => $request->horaInicio,
                'horaFin' => $request->horaFin,
                'capacidad' => $request->capacidad,
                'idEntrenador' => $request->idEntrenador,
                'idAutor' => Auth::id(),
            ]);
    
            return response()->json(['success' => 'Sección creada exitosamente.']);
        } catch (\Exception $e) {
            // Log del error para revisión
            \Log::error($e->getMessage());
            return response()->json(['error' => 'Error en el servidor, por favor intenta de nuevo.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'idServicio' => 'required|exists:servicios,idServicio',
            'fechaInicio' => 'required|date|before_or_equal:fechaFin',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
            'horaInicio' => 'required|date_format:H:i|before:horaFin',
            'horaFin' => 'required|date_format:H:i|after:horaInicio',
            'capacidad' => 'required|integer|min:1',
            'idEntrenador' => 'nullable|exists:entrenadores,idEntrenador',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $seccion = Seccion::findOrFail($id);
        $seccion->update([
            'idServicio' => $request->idServicio,
            'fechaInicio' => $request->fechaInicio,
            'fechaFin' => $request->fechaFin,
            'horaInicio' => $request->horaInicio,
            'horaFin' => $request->horaFin,
            'capacidad' => $request->capacidad,
            'idEntrenador' => $request->idEntrenador,
            'idAutor' => Auth::id(),
        ]);

        return response()->json(['success' => 'Sección actualizada exitosamente.']);
    }

    public function destroy($id)
    {
        $seccion = Seccion::findOrFail($id);
        $seccion->update(['eliminado' => 0]);

        return response()->json(['success' => 'Sección eliminada exitosamente.']);
    }
}
