<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscripcion;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IngresosVendedorExport;
use DB;

class ReportesIngresosVendedorController extends Controller
{
    public function index(Request $request)
    {
        // Filtros por fecha
        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');

        // Si no se han seleccionado filtros de fecha, devolver una colección vacía
        if (!$request->filled('fechaInicio') || !$request->filled('fechaFin')) {
            // Colección vacía cuando no hay filtros aplicados
            $ingresosPorVendedor = collect();
            $totalInscripciones = 0;
            $totalGanado = 0;
        } else {
            // Consulta de inscripciones agrupadas por vendedor
            $ingresosPorVendedor = Inscripcion::select(
                'usuarios.nombreUsuario as vendedor',
                DB::raw('COUNT(inscripciones.idInscripcion) as totalInscripciones'),
                DB::raw('SUM(inscripciones.totalPago) as totalGanado')
            )
                ->join('usuarios', 'inscripciones.idUsuario', '=', 'usuarios.idUsuario')
                ->when($fechaInicio, function ($query) use ($fechaInicio) {
                    $query->whereDate('inscripciones.fechaInscripcion', '>=', $fechaInicio);
                })
                ->when($fechaFin, function ($query) use ($fechaFin) {
                    $query->whereDate('inscripciones.fechaInscripcion', '<=', $fechaFin);
                })
                ->groupBy('usuarios.nombreUsuario')
                ->orderBy('totalGanado', 'desc') // Ordenar por los ingresos más altos
                ->get();

            // Calcular los totales generales
            $totalInscripciones = $ingresosPorVendedor->sum('totalInscripciones');
            $totalGanado = $ingresosPorVendedor->sum('totalGanado');
        }

        return view('admin.reportes.ingresos-vendedor', compact('ingresosPorVendedor', 'totalInscripciones', 'totalGanado', 'fechaInicio', 'fechaFin'));
    }

    // Exportar a PDF
    // Exportar a PDF
    public function exportarPDF(Request $request)
    {
        $ingresosPorVendedor = $this->filtrarIngresosPorVendedor($request);
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;

        $pdf = Pdf::loadView('admin.reportes.ingresos-vendedor-pdf', compact('ingresosPorVendedor', 'fechaInicio', 'fechaFin'))
            ->setPaper('a4', 'portrait');
            $pdf->getDomPDF()->set_option("enable_remote", true);
            $pdf->getDomPDF()->set_option("isRemoteEnabled", true);
    
            // Establecer la ruta base para las imágenes
            $pdf->getDomPDF()->set_option("chroot", public_path());
        return $pdf->stream('reporte_ingresos_vendedor.pdf');
    }


    // Exportar a Excel
    // public function exportarExcel(Request $request)
    // {
    //      $ingresosPorVendedor = $this->filtrarIngresosPorVendedor($request);
    //    return Excel::download(new IngresosVendedorExport($ingresosPorVendedor), 'reporte_ingresos_vendedor.xlsx');
    // }

    // Función para aplicar filtros
    private function filtrarIngresosPorVendedor(Request $request)
    {
        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');

        return Inscripcion::select(
            'usuarios.nombreUsuario as vendedor',
            DB::raw('COUNT(inscripciones.idInscripcion) as totalInscripciones'),
            DB::raw('SUM(inscripciones.totalPago) as totalGanado')
        )
            ->join('usuarios', 'inscripciones.idUsuario', '=', 'usuarios.idUsuario')
            ->when($fechaInicio, function ($query) use ($fechaInicio) {
                $query->whereDate('inscripciones.fechaInscripcion', '>=', $fechaInicio);
            })
            ->when($fechaFin, function ($query) use ($fechaFin) {
                $query->whereDate('inscripciones.fechaInscripcion', '<=', $fechaFin);
            })
            ->groupBy('usuarios.nombreUsuario')
            ->orderBy('totalGanado', 'desc')
            ->get();
    }
}
