<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;// Si usas DomPDF para exportar
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InscripcionesExport;
use TCPDF;

class ReporteInscripcionController extends Controller
{
    public function index(Request $request)
    {
        $query = Inscripcion::query();

        // Filtros avanzados
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fechaInicio', [$request->fecha_inicio, $request->fecha_fin]);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('estadoPago')) {
            $query->where('estadoPago', $request->estadoPago);
        }

        if ($request->filled('membresia_id')) {
            $query->where('membresia_id', $request->membresia_id);
        }

        if ($request->filled('cliente_nombre')) {
            $query->whereHas('cliente', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->cliente_nombre . '%');
            });
        }

        // Recuperar inscripciones con relaciones
        $inscripciones = $query->with('cliente', 'membresia')->get();

        // Totales
        $totalInscripciones = $inscripciones->count();
        $totalPagos = $inscripciones->where('estadoPago', 'completado')->sum('montoPago');

        return view('admin.reportes.inscripciones', compact('inscripciones', 'totalInscripciones', 'totalPagos'));
    }



    private function getFilteredInscripciones(Request $request)
    {
        // Filtros para obtener las inscripciones
        $query = Inscripcion::query();
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fechaInicio', [$request->fecha_inicio, $request->fecha_fin]);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('estadoPago')) {
            $query->where('estadoPago', $request->estadoPago);
        }
        if ($request->filled('membresia_id')) {
            $query->where('membresia_id', $request->membresia_id);
        }
        if ($request->filled('cliente_nombre')) {
            $query->whereHas('cliente', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->cliente_nombre . '%');
            });
        }
        return $query->with('cliente', 'membresia')->get();
    }
    public function exportExcel()
    {
        return Excel::download(new InscripcionesExport, 'reporte-inscripciones.xlsx');
    }

    // Generar PDF
    public function exportPDF(Request $request)
    {
        // Obtén las inscripciones filtradas
        $inscripciones = $this->getFilteredInscripciones($request);

        // Calcular totales
        $totalInscripciones = $inscripciones->count();
        $totalPagos = $inscripciones->where('estadoPago', 'completado')->sum('montoPago');


        // Generar el PDF con las inscripciones y totales
        $pdf = pdf::loadView('admin.reportes.pdf', compact('inscripciones', 'totalInscripciones', 'totalPagos'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        // Descargar el PDF
        return $pdf->download('reporte-inscripciones.pdf');
    }

    // Generar Excel




}
