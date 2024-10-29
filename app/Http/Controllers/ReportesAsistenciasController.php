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
        // Iniciar la consulta
        $asistencias = Asistencia::query()
            ->join('clientes', 'asistencias.idCliente', '=', 'clientes.idCliente')
            ->select(
                'asistencias.fechaAsistencia',
                'clientes.nombre as clienteNombre',
                'asistencias.metodoRegistro',
                'asistencias.estado'
            );

        // Verificar si se aplicaron filtros
        if ($request->filled('fechaInicio') || $request->filled('fechaFin')) {
            // Aplicar los filtros según las fechas proporcionadas
            $asistencias = $asistencias
                ->when($request->filled('fechaInicio'), function ($query) use ($request) {
                    $query->whereDate('asistencias.fechaAsistencia', '>=', $request->fechaInicio);
                })
                ->when($request->filled('fechaFin'), function ($query) use ($request) {
                    $query->whereDate('asistencias.fechaAsistencia', '<=', $request->fechaFin);
                })
                ->get();
        } else {
            // Si no se aplicaron filtros, no obtener resultados
            $asistencias = collect(); // Retorna una colección vacía
        }

        // Calcular las estadísticas solo si hay datos
        $totalAsistencias = $asistencias->count();
        $totalQR = $asistencias->where('metodoRegistro', 'QR')->count();
        $totalManual = $asistencias->where('metodoRegistro', 'manual')->count();

        return view('admin.reportes.asistencias', compact('asistencias', 'totalAsistencias', 'totalQR', 'totalManual'));
    }

    public function exportarPDF(Request $request)
    {
        $asistencias = $this->filtrarAsistencias($request);

        // Capturar las fechas para el reporte
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;

        // Generar el PDF en orientación vertical (portrait)
        $pdf = Pdf::loadView('admin.reportes.asistencias-pdf', compact('asistencias', 'fechaInicio', 'fechaFin'))
            ->setPaper('a4', 'portrait');

        // Configurar DomPDF para permitir archivos externos
        $pdf->getDomPDF()->set_option("enable_remote", true);
        $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

        // Establecer la ruta base para las imágenes
        $pdf->getDomPDF()->set_option("chroot", public_path());

        // Devolver el PDF para verlo en una nueva pestaña
        return $pdf->stream('reporte_asistencias.pdf');
    }

    // Exportar a Excel
    public function exportarExcel(Request $request)
    {
        $asistencias = $this->filtrarAsistencias($request);
        return Excel::download(new AsistenciasExport($asistencias), 'reporte_asistencias.xlsx');
    }

    // Filtrar asistencias según los parámetros de búsqueda
    private function filtrarAsistencias(Request $request)
    {
        $asistencias = Asistencia::query()
            ->join('clientes', 'asistencias.idCliente', '=', 'clientes.idCliente')
            ->select(
                'asistencias.fechaAsistencia',
                'clientes.nombre as clienteNombre',
                'asistencias.metodoRegistro',
                'asistencias.estado'
            )
            ->when($request->filled('fechaInicio'), function ($query) use ($request) {
                $query->whereDate('asistencias.fechaAsistencia', '>=', $request->fechaInicio);
            })
            ->when($request->filled('fechaFin'), function ($query) use ($request) {
                $query->whereDate('asistencias.fechaAsistencia', '<=', $request->fechaFin);
            })
            ->get();

        return $asistencias;
    }
}
