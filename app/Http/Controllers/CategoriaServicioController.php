<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaServicio;

class CategoriaServicioController extends Controller
{
    // Método para mostrar las categorías
    public function index()
    {
        $categorias = CategoriaServicio::where('estado', 1)->get();
        return view('admin.categorias.index', compact('categorias'));
    }

    // Método para almacenar una nueva categoría
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'estado' => 'required|boolean'
        ]);

        CategoriaServicio::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado ? 1 : 0,
        ]);

        return response()->json(['success' => 'Categoría creada exitosamente']);
    }

    // Método para actualizar una categoría
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'estado' => 'required|boolean'
        ]);

        $categoria = CategoriaServicio::findOrFail($id);

        $categoria->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado ? 1 : 0,
        ]);

        return response()->json(['success' => 'Categoría actualizada exitosamente']);
    }

    // Método para eliminar una categoría (soft delete)
    public function destroy($id)
    {
        $categoria = CategoriaServicio::findOrFail($id);
        $categoria->update(['estado' => 0]);

        return response()->json(['success' => 'Categoría eliminada exitosamente']);
    }
}
