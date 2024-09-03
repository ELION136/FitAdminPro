<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membresia;
use Barryvdh\DomPDF\Facade\Pdf;

class MembresiaController extends Controller
{
    public function historial(Request $request)
    {
        // Obtener todas las membresías con sus relaciones (cliente y plan)
        $query = Membresia::with(['cliente', 'planMembresia']);

        if ($request->has('fecha_inicio') && $request->has('fecha_fin')) {
            $query->whereBetween('fechaInicio', [$request->fecha_inicio, $request->fecha_fin]);
        }

        $membresias = $query->get();

        return view('admin.membresias.historial', compact('membresias'));
    }

    public function generarPDF(Request $request)
    {
        $query = Membresia::with(['cliente', 'planMembresia']);

        if ($request->has('fecha_inicio') && $request->has('fecha_fin')) {
            $query->whereBetween('fechaInicio', [$request->fecha_inicio, $request->fecha_fin]);
        }

        $membresias = $query->get();

        $pdf = Pdf::loadView('admin.membresias.generarPDF', compact('membresias'));
        return $pdf->download('reporte_membresias.pdf');
    }
    public function generarCredencial($id)
    {
        $membresia = Membresia::with(['cliente'])->findOrFail($id);

        $pdf = Pdf::loadView('admin.membresias.generarCredencial', compact('membresia'));
        return $pdf->download('credencial_' . $membresia->cliente->nombre . '.pdf');
    }
}
