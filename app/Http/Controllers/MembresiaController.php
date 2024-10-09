<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membresia;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\Auth;

class MembresiaController extends Controller
{
    public function index()
    {
        $membresias = Membresia::where('eliminado', 1)->get();
        return view('admin.membresias.index', compact('membresias'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'duracionDias' => 'required|integer|min:1|max:365', // Duración mínima de 1 día y máxima de 1 año
            'precio' => 'required|numeric|min:0|max:10000', // Precio mínimo de 0 y máximo de 10,000 BOB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Membresia::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'duracionDias' => $request->duracionDias,
            'precio' => $request->precio,
            'idAutor' => auth()->id(),
        ]);

        return response()->json(['success' => 'Membresía creada exitosamente.']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'duracionDias' => 'required|integer|min:1|max:365', // Duración mínima de 1 día y máxima de 1 año
            'precio' => 'required|numeric|min:0|max:10000', // Precio mínimo de 0 y máximo de 10,000 BOB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $membresia = Membresia::findOrFail($id);
        $membresia->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'duracionDias' => $request->duracionDias,
            'precio' => $request->precio,
            'idAutor' => auth()->id(),
        ]);

        return response()->json(['success' => 'Membresía actualizada exitosamente.']);
    }

    public function destroy($id)
    {
        $membresia = Membresia::findOrFail($id);
        $membresia->update(['eliminado' => 0]);

        return response()->json(['success' => 'Membresía eliminada exitosamente.']);
    }
}
