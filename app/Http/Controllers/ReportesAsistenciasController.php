<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Cliente;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AsistenciasExport;


class ReportesAsistenciasController extends Controller
{
    // Mostrar el reporte de asistencias con filtros y estadísticas
    public function index(Request $request)
    {
        // Obtener la fecha actual como valor predeterminado
        $fechaInicio = $request->input('fechaInicio', Carbon::now()->format('Y-m-d'));
        $fechaFin = $request->input('fechaFin', Carbon::now()->format('Y-m-d'));

        // Consultar asistencias dentro del rango de fechas
        $asistencias = Asistencia::query()
            ->join('clientes', 'asistencias.idCliente', '=', 'clientes.idCliente')
            ->select(
                'asistencias.fechaAsistencia',
                'clientes.nombre as clienteNombre',
                'asistencias.metodoRegistro',
                'asistencias.estado'
            )
            ->whereDate('asistencias.fechaAsistencia', '>=', $fechaInicio)
            ->whereDate('asistencias.fechaAsistencia', '<=', $fechaFin)
            ->get();

        // Calcular las estadísticas de asistencias
        $totalAsistencias = $asistencias->count();
        $totalQR = $asistencias->where('metodoRegistro', 'QR')->count();
        $totalManual = $asistencias->where('metodoRegistro', 'manual')->count();

        // Calcular el total de asistencias del día actual
        $asistenciasHoy = Asistencia::whereDate('fechaAsistencia', Carbon::today())->count();

        return view('admin.reportes.asistencias', compact(
            'asistencias',
            'totalAsistencias',
            'totalQR',
            'totalManual',
            'fechaInicio',
            'fechaFin',
            'asistenciasHoy'
        ));
    }

    // Exportar el reporte de asistencias a PDF
    public function exportarPDF(Request $request)
    {
        $fechaInicio = $request->input('fechaInicio', Carbon::now()->format('Y-m-d'));
        $fechaFin = $request->input('fechaFin', Carbon::now()->format('Y-m-d'));

        $asistencias = $this->filtrarAsistencias($fechaInicio, $fechaFin);

        $pdf = Pdf::loadView('admin.reportes.asistencias-pdf', compact('asistencias', 'fechaInicio', 'fechaFin'))
            ->setPaper('a4', 'portrait');

        // Configuraciones adicionales para DomPDF
        $pdf->getDomPDF()->set_option("enable_remote", true);
        $pdf->getDomPDF()->set_option("isRemoteEnabled", true);
        $pdf->getDomPDF()->set_option("chroot", public_path());

        return $pdf->stream('reporte_asistencias.pdf');
    }

    // Exportar el reporte de asistencias a Excel
    public function exportarExcel(Request $request)
    {
        $fechaInicio = $request->input('fechaInicio', Carbon::now()->format('Y-m-d'));
        $fechaFin = $request->input('fechaFin', Carbon::now()->format('Y-m-d'));

        $asistencias = $this->filtrarAsistencias($fechaInicio, $fechaFin);
        return Excel::download(new AsistenciasExport($asistencias), 'reporte_asistencias.xlsx');
    }

    // Filtrar asistencias según el rango de fechas
    private function filtrarAsistencias($fechaInicio, $fechaFin)
    {
        return Asistencia::query()
            ->join('clientes', 'asistencias.idCliente', '=', 'clientes.idCliente')
            ->select(
                'asistencias.fechaAsistencia',
                'clientes.nombre as clienteNombre',
                'asistencias.metodoRegistro',
                'asistencias.estado'
            )
            ->whereDate('asistencias.fechaAsistencia', '>=', $fechaInicio)
            ->whereDate('asistencias.fechaAsistencia', '<=', $fechaFin)
            ->get();
    }
}
