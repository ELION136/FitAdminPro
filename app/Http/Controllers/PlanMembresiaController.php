<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanMembresia;

use Auth;


class PlanMembresiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $planes = PlanMembresia::where('eliminado', 1)->get();
        return view('admin.planes.index', compact('planes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombrePlan' => 'required|max:50',
            'descripcion' => 'nullable|max:255',
            'duracion' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0.01',
        ]);

        $plan = new PlanMembresia($request->all());
        $plan->idAutor = auth()->id();
        $plan->save();

        return redirect()->route('admin.planes.index')->with('success', 'El plan ha sido creado exitosamente');
    }

    public function update(Request $request, $idPlan)
    {
        $request->validate([
            'nombrePlan' => 'required|max:50',
            'descripcion' => 'nullable|max:255',
            'duracion' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0.01',
        ]);

        $plan = PlanMembresia::findOrFail($idPlan);
        $plan->update($request->all());
        $plan->idAutor = auth()->id();
        $plan->save();

        return redirect()->route('admin.planes.index')->with('success', 'El plan ha sido actualizado exitosamente');
    }

    public function destroy($idPlan)
    {
        $plan = PlanMembresia::findOrFail($idPlan);
        $plan->eliminado = 0;  // Eliminación lógica
        $plan->save();

        return redirect()->route('admin.planes.index')->with('success', 'El plan ha sido eliminado exitosamente');
    }
}
