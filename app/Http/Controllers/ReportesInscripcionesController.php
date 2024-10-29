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
        // Filtros de fecha: año y mes
        $anio = $request->input('anio');
        $mes = $request->input('mes');
    
        // Si no se han seleccionado filtros de año y mes, devolver una colección vacía
        if (!$request->filled('anio') || !$request->filled('mes')) {
            // Tabla vacía y estadísticas vacías
            $inscripciones = collect(); // Colección vacía
            $totalInscripciones = 0;
            $totalGanado = 0;
        } else {
            // Consulta para obtener las inscripciones agrupadas por mes y año
            $inscripciones = Inscripcion::select(
                DB::raw('YEAR(fechaInscripcion) as anio'),
                DB::raw('MONTH(fechaInscripcion) as mes'),
                DB::raw('COUNT(*) as totalInscripciones'),
                DB::raw('SUM(totalPago) as totalGanado') // Suma del total pagado
            )
            ->when($request->filled('anio'), function ($query) use ($request) {
                $query->whereYear('fechaInscripcion', $request->anio);
            })
            ->when($request->filled('mes'), function ($query) use ($request) {
                $query->whereMonth('fechaInscripcion', $request->mes);
            })
            ->groupBy('anio', 'mes')
            ->orderBy('anio', 'desc')
            ->orderBy('mes', 'desc')
            ->get();
    
            // Calcular el total de inscripciones del mes y año seleccionado
            $totalInscripciones = $inscripciones->sum('totalInscripciones');
            $totalGanado = $inscripciones->sum('totalGanado'); // Total ganado calculado
        }
    
        // Consulta para obtener el reporte total anual agrupado por año
        $reporteAnual = Inscripcion::select(
            DB::raw('YEAR(fechaInscripcion) as anio'),
            DB::raw('COUNT(*) as totalInscripciones'),
            DB::raw('SUM(totalPago) as totalGanado')  // Suma del total pagado anual
        )
        ->groupBy('anio')
        ->orderBy('anio', 'desc')
        ->get();
    
        return view('admin.reportes.inscripciones-mes-anio', compact('inscripciones', 'anio', 'mes', 'totalInscripciones', 'totalGanado', 'reporteAnual'));
    }
    


    // Exportar a PDF
    public function exportarPDF(Request $request)
    {
        $inscripciones = $this->filtrarInscripciones($request);
        $anio = $request->anio;
        $mes = $request->mes;

        // Calcular el total ganado sumando los pagos de las inscripciones
        $totalGanado = $inscripciones->sum('totalPagado'); // O cambia a 'totalPago' si es el campo correcto

        $pdf = Pdf::loadView('admin.reportes.inscripciones-mes-anio-pdf', compact('inscripciones', 'anio', 'mes', 'totalGanado'))
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
        $inscripciones = $this->filtrarInscripciones($request);
        return Excel::download(new InscripcionesExport($inscripciones), 'reporte_inscripciones_mes_anio.xlsx');
    }

    // Filtrar inscripciones según los parámetros de búsqueda
    private function filtrarInscripciones(Request $request)
    {
        return Inscripcion::select(
            DB::raw('YEAR(fechaInscripcion) as anio'),
            DB::raw('MONTH(fechaInscripcion) as mes'),
            DB::raw('COUNT(*) as totalInscripciones'),
            DB::raw('SUM(totalPago) as totalPagado')  // Suma del total pagado
        )
            ->when($request->filled('anio'), function ($query) use ($request) {
                $query->whereYear('fechaInscripcion', $request->anio);
            })
            ->when($request->filled('mes'), function ($query) use ($request) {
                $query->whereMonth('fechaInscripcion', $request->mes);
            })
            ->groupBy('anio', 'mes')
            ->orderBy('anio', 'desc')
            ->orderBy('mes', 'desc')
            ->get();
    }


}
