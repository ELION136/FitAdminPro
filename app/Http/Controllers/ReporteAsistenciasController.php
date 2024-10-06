<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Cliente;
use App\Exports\AsistenciasExport;
use Maatwebsite\Excel\Facades\Excel;

use Barryvdh\DomPDF\Facade\Pdf;

class ReporteAsistenciasController extends Controller
{
    // Mostrar vista de reportes con filtros
    public function index(Request $request)
    {
        $query = Asistencia::query();

        // Aplicar filtros de búsqueda
        if ($request->filled('idCliente')) {
            $query->where('idCliente', $request->idCliente);
        }

        if ($request->filled('fechaInicio') && $request->filled('fechaFin')) {
            $query->whereBetween('fecha', [$request->fechaInicio, $request->fechaFin]);
        }

        if ($request->filled('horaEntrada')) {
            $query->where('horaEntrada', '>=', $request->horaEntrada);
        }

        if ($request->filled('horaSalida')) {
            $query->where('horaSalida', '<=', $request->horaSalida);
        }

        // Filtro de eliminados
        $query->where('eliminado', 1);

        $asistencias = $query->get();

        return view('admin.reportes.asistencias', compact('asistencias'));
    }

    // Exportar a Excel
    public function exportExcel(Request $request)
    {
        $asistencias = $this->getFilteredAsistencias($request);
        return Excel::download(new AsistenciasExport($asistencias), 'asistencias.xlsx');
    }

    // Exportar a PDF
    public function exportPDF(Request $request)
    {
        $asistencias = $this->getFilteredAsistencias($request);
        $pdf = pdf::loadView('admin.asistencias.reporte_pdf', compact('asistencias'));
        return $pdf->download('asistencias.pdf');
    }

    // Método para aplicar los filtros en la exportación
    private function getFilteredAsistencias(Request $request)
    {
        $query = Asistencia::query();

        if ($request->filled('idCliente')) {
            $query->where('idCliente', $request->idCliente);
        }

        if ($request->filled('fechaInicio') && $request->filled('fechaFin')) {
            $query->whereBetween('fecha', [$request->fechaInicio, $request->fechaFin]);
        }

        if ($request->filled('horaEntrada')) {
            $query->where('horaEntrada', '>=', $request->horaEntrada);
        }

        if ($request->filled('horaSalida')) {
            $query->where('horaSalida', '<=', $request->horaSalida);
        }

        $query->where('eliminado', 1);

        return $query->get();
    }

    public function searchClientes(Request $request)
    {
        $search = $request->input('term');

        $clientes = Cliente::where('nombre', 'like', '%' . $search . '%')
            ->get(['idCliente', 'nombre']);  // Seleccionamos solo los campos necesarios

        return response()->json($clientes);
    }

}
