<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membresia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\Auth;

class MembresiaController extends Controller
{
    public function index()
    {
        // Obtener todas las membresías activas (no eliminadas)
        $membresias = Membresia::where('eliminado', 1)->get();
        return view('admin.membresias.index', compact('membresias'));
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:50',
                'descripcion' => 'nullable|string',
                'duracionDias' => 'required|integer|min:1|max:365',
                'precio' => 'required|numeric|min:0',
                'fechaInicio' => 'required|date',
            ]);
            
            // Si la validación falla, devolver los errores
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Calcular la fecha de fin basada en la duración
            $fechaFin = Carbon::parse($request->fechaInicio)->addDays($request->duracionDias);

            // Crear una nueva membresía
            Membresia::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'duracionDias' => $request->duracionDias,
                'precio' => $request->precio,
                'fechaInicio' => $request->fechaInicio,
                'fechaFin' => $fechaFin,  // Fecha de fin calculada
                'idAutor' => auth()->id(),
            ]);

            return response()->json(['success' => 'Membresía creada exitosamente.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hubo un error al crear la membresía: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:50',
                'descripcion' => 'nullable|string',
                'duracionDias' => 'required|integer|min:1|max:365',
                'precio' => 'required|numeric|min:0',
                'fechaInicio' => 'required|date',
            ]);

            // Si la validación falla, devolver los errores
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Buscar la membresía a actualizar
            $membresia = Membresia::findOrFail($id);

            // Calcular la nueva fecha de fin basada en la fecha de inicio y duración
            $fechaFin = Carbon::parse($request->fechaInicio)->addDays($request->duracionDias);

            // Actualizar la membresía
            $membresia->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'duracionDias' => $request->duracionDias,
                'precio' => $request->precio,
                'fechaInicio' => $request->fechaInicio,
                'fechaFin' => $fechaFin,  // Fecha de fin calculada
                'idAutor' => auth()->id(),
            ]);

            return response()->json(['success' => 'Membresía actualizada exitosamente.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hubo un error al actualizar la membresía: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Buscar la membresía a eliminar (soft delete)
            $membresia = Membresia::findOrFail($id);
            $membresia->update(['eliminado' => 0]);

            return response()->json(['success' => 'Membresía eliminada exitosamente.']);
        } catch (\Exception $e) {
            // Capturar y registrar el error
            return response()->json(['error' => 'Hubo un error al eliminar la membresía: ' . $e->getMessage()], 500);
        }
    }
}
