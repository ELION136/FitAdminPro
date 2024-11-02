<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrenador;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EntrenadoresExport;

class ReportesEntrenadoresController extends Controller
{
    public function index(Request $request)
    {
        $entrenadores = Entrenador::query();

        // Cálculo de totales para las cards
        $totalEntrenadores = Entrenador::count();
        $totalHombres = Entrenador::where('genero', 'Masculino')->count();
        $totalMujeres = Entrenador::where('genero', 'Femenino')->count();

        // Aplicar filtros solo si se han enviado
        if ($request->filled('nombre') || $request->filled('especialidad') || $request->filled('genero') || $request->filled('fechaCreacionInicio') || $request->filled('fechaCreacionFin')) {

            if ($request->filled('nombre')) {
                $entrenadores->where('nombre', 'like', '%' . $request->nombre . '%');
            }

            if ($request->filled('especialidad')) {
                $entrenadores->where('especialidad', $request->especialidad);
            }

            if ($request->filled('genero') && $request->genero != '') {
                $entrenadores->where('genero', $request->genero);
            }
            if ($request->filled('fechaCreacionInicio') && $request->filled('fechaCreacionFin')) {
                $entrenadores->whereBetween('fechaCreacion', [$request->fechaCreacionInicio, $request->fechaCreacionFin]);
            }

            $entrenadores = $entrenadores->get();
        } else {
            $entrenadores = collect();
        }

        // Establecer valores por defecto para las fechas en la vista
        $fechaActual = Carbon::now()->format('Y-m-d');
        if (!$request->filled('fechaCreacionInicio')) {
            $request->merge(['fechaCreacionInicio' => $fechaActual]);
        }
        if (!$request->filled('fechaCreacionFin')) {
            $request->merge(['fechaCreacionFin' => $fechaActual]);
        }

        return view('admin.reportes.entrenadores', compact('entrenadores', 'totalEntrenadores', 'totalHombres', 'totalMujeres'));
    }

    // Exportar a PDF
    public function exportarPDF(Request $request)
    {
        $entrenadores = $this->filtrarEntrenadores($request);
        $fechaCreacionInicio = $request->fechaCreacionInicio;
        $fechaCreacionFin = $request->fechaCreacionFin;

        $pdf = Pdf::loadView('admin.reportes.entrenadores-pdf', compact('entrenadores', 'fechaCreacionInicio', 'fechaCreacionFin'))
            ->setPaper('a4', 'portrait'); // Orientación vertical
        $pdf->getDomPDF()->set_option("enable_remote", true);
        $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

        // Establecer la ruta base para las imágenes
        $pdf->getDomPDF()->set_option("chroot", public_path());

        return $pdf->stream('reporte_entrenadores.pdf');
    }

    // Exportar a Excel
    public function exportarExcel(Request $request)
    {
        $entrenadores = $this->filtrarEntrenadores($request);
        return Excel::download(new EntrenadoresExport($entrenadores), 'reporte_entrenadores.xlsx');
    }

    private function filtrarEntrenadores(Request $request)
    {
        $entrenadores = Entrenador::query();

        if ($request->filled('nombre')) {
            $entrenadores->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('especialidad')) {
            $entrenadores->where('especialidad', $request->especialidad);
        }

        if ($request->filled('genero') && $request->genero != '') {
            $entrenadores->where('genero', $request->genero);
        }
        if ($request->filled('fechaCreacionInicio') && $request->filled('fechaCreacionFin')) {
            $entrenadores->whereBetween('fechaCreacion', [$request->fechaCreacionInicio, $request->fechaCreacionFin]);
        }

        return $entrenadores->get();
    }
}
