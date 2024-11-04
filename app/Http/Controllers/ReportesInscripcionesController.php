<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscripcion;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InscripcionesExport;
use DB;

class ReportesInscripcionesController extends Controller
{
    public function index(Request $request)
    {
        // Fecha actual como predeterminado
        $fechaActual = Carbon::now();
        $anio = $request->input('anio', $fechaActual->year);
        $mes = $request->input('mes', $fechaActual->month);
        $dia = $request->input('dia', $fechaActual->day);

        // Si no se han seleccionado filtros de año, mes y día, devolver una colección vacía
        if (!$request->filled('anio') && !$request->filled('mes') && !$request->filled('dia')) {
            $inscripciones = collect(); // Colección vacía
            $totalInscripciones = 0;
            $totalGanado = 0;
        } else {
            // Consulta para obtener las inscripciones agrupadas por fecha
            $inscripciones = Inscripcion::select(
                DB::raw('YEAR(fechaInscripcion) as anio'),
                DB::raw('MONTH(fechaInscripcion) as mes'),
                DB::raw('DAY(fechaInscripcion) as dia'),
                DB::raw('COUNT(*) as totalInscripciones'),
                DB::raw('SUM(totalPago) as totalGanado') // Suma del total pagado
            )
                ->whereYear('fechaInscripcion', $anio)
                ->whereMonth('fechaInscripcion', $mes)
                ->whereDay('fechaInscripcion', $dia)
                ->groupBy('anio', 'mes', 'dia')
                ->orderBy('anio', 'desc')
                ->orderBy('mes', 'desc')
                ->orderBy('dia', 'desc')
                ->get();

            // Calcular el total de inscripciones y el total ganado
            $totalInscripciones = $inscripciones->sum('totalInscripciones');
            $totalGanado = $inscripciones->sum('totalGanado');
        }

        // Consulta para obtener el reporte anual agrupado por año
        $reporteAnual = Inscripcion::select(
            DB::raw('YEAR(fechaInscripcion) as anio'),
            DB::raw('COUNT(*) as totalInscripciones'),
            DB::raw('SUM(totalPago) as totalGanado')  // Suma del total pagado anual
        )
            ->groupBy('anio')
            ->orderBy('anio', 'desc')
            ->get();

        return view('admin.reportes.inscripciones-mes-anio', compact('inscripciones', 'anio', 'mes', 'dia', 'totalInscripciones', 'totalGanado', 'reporteAnual'));
    }

    // Exportar a PDF
    public function exportarPDF(Request $request)
    {
        // Obtener las inscripciones filtradas
        $inscripciones = $this->filtrarInscripciones($request->anio, $request->mes, $request->dia);
        $anio = $request->anio;
        $mes = $request->mes;
        $dia = $request->dia;

        // Calcular el total ganado
        $totalGanado = $inscripciones->sum('totalPagado');

        // Obtener el reporte anual
        $reporteAnual = Inscripcion::select(
            DB::raw('YEAR(fechaInscripcion) as anio'),
            DB::raw('COUNT(*) as totalInscripciones'),
            DB::raw('SUM(totalPago) as totalGanado')
        )
            ->groupBy('anio')
            ->orderBy('anio', 'desc')
            ->get();

        // Cargar la vista y generar el PDF
        $pdf = Pdf::loadView('admin.reportes.inscripciones-mes-anio-pdf', compact('inscripciones', 'anio', 'mes', 'dia', 'totalGanado', 'reporteAnual'))
            ->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_remote", true);
        $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

        // Establecer la ruta base para las imágenes
        $pdf->getDomPDF()->set_option("chroot", public_path());
        return $pdf->stream('reporte_inscripciones_mes_anio.pdf');
    }


    // Exportar a Excel
    public function exportarExcel(Request $request)
    {
        // Fecha actual como predeterminado
        $fechaActual = Carbon::now();
        $anio = $request->input('anio', $fechaActual->year);
        $mes = $request->input('mes', $fechaActual->month);
        $dia = $request->input('dia', $fechaActual->day);

        $inscripciones = $this->filtrarInscripciones($anio, $mes, $dia);
        return Excel::download(new InscripcionesExport($inscripciones), 'reporte_inscripciones_mes_anio.xlsx');
    }

    // Filtrar inscripciones según los parámetros de búsqueda
    private function filtrarInscripciones($anio, $mes, $dia)
    {
        return Inscripcion::select(
            DB::raw('YEAR(fechaInscripcion) as anio'),
            DB::raw('MONTH(fechaInscripcion) as mes'),
            DB::raw('DAY(fechaInscripcion) as dia'),
            DB::raw('COUNT(*) as totalInscripciones'),
            DB::raw('SUM(totalPago) as totalPagado')  // Suma del total pagado
        )
            ->whereYear('fechaInscripcion', $anio)
            ->whereMonth('fechaInscripcion', $mes)
            ->whereDay('fechaInscripcion', $dia)
            ->groupBy('anio', 'mes', 'dia')
            ->orderBy('anio', 'desc')
            ->orderBy('mes', 'desc')
            ->orderBy('dia', 'desc')
            ->get();
    }


}
