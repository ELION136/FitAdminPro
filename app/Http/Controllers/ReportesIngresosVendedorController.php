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
        // Definir la fecha actual
        $fechaHoy = Carbon::now()->format('Y-m-d');

        // Filtros por fecha, usando la fecha actual como predeterminado
        $fechaInicio = $request->input('fechaInicio', $fechaHoy);
        $fechaFin = $request->input('fechaFin', $fechaHoy);

        // Consulta de inscripciones agrupadas por vendedor
        $ingresosPorVendedor = Inscripcion::select(
            'usuarios.nombreUsuario as vendedor',
            DB::raw('COUNT(inscripciones.idInscripcion) as totalInscripciones'),
            DB::raw('SUM(inscripciones.totalPago) as totalGanado')
        )
            ->join('usuarios', 'inscripciones.idUsuario', '=', 'usuarios.idUsuario')
            ->whereDate('inscripciones.fechaInscripcion', '>=', $fechaInicio)
            ->whereDate('inscripciones.fechaInscripcion', '<=', $fechaFin)
            ->groupBy('usuarios.nombreUsuario')
            ->orderBy('totalGanado', 'desc') // Ordenar por los ingresos más altos
            ->get();

        // Calcular los totales generales
        $totalInscripciones = $ingresosPorVendedor->sum('totalInscripciones');
        $totalGanado = $ingresosPorVendedor->sum('totalGanado');

        return view('admin.reportes.ingresos-vendedor', compact('ingresosPorVendedor', 'totalInscripciones', 'totalGanado', 'fechaInicio', 'fechaFin'));
    }


    // Exportar a PDF
    // Exportar a PDF
    public function exportarPDF(Request $request)
    {
        // Definir la fecha actual
        $fechaHoy = Carbon::now()->format('Y-m-d');

        // Filtros de fecha para la exportación, usando la fecha actual como predeterminado
        $fechaInicio = $request->input('fechaInicio', $fechaHoy);
        $fechaFin = $request->input('fechaFin', $fechaHoy);

        // Obtener los datos filtrados
        $ingresosPorVendedor = $this->filtrarIngresosPorVendedorConFechas($fechaInicio, $fechaFin);

        // Calcular los totales generales
        $totalInscripciones = $ingresosPorVendedor->sum('totalInscripciones');
        $totalGanado = $ingresosPorVendedor->sum('totalGanado');

        $pdf = Pdf::loadView('admin.reportes.ingresos-vendedor-pdf', compact('ingresosPorVendedor', 'fechaInicio', 'fechaFin', 'totalInscripciones', 'totalGanado'))
            ->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_remote", true);
        $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

        // Establecer la ruta base para las imágenes
        $pdf->getDomPDF()->set_option("chroot", public_path());

        return $pdf->stream('reporte_ingresos_vendedor.pdf');
    }

    // Función modificada para aplicar filtros con fechas específicas
    private function filtrarIngresosPorVendedorConFechas($fechaInicio, $fechaFin)
    {
        return Inscripcion::select(
            'usuarios.nombreUsuario as vendedor',
            DB::raw('COUNT(inscripciones.idInscripcion) as totalInscripciones'),
            DB::raw('SUM(inscripciones.totalPago) as totalGanado')
        )
            ->join('usuarios', 'inscripciones.idUsuario', '=', 'usuarios.idUsuario')
            ->whereDate('inscripciones.fechaInscripcion', '>=', $fechaInicio)
            ->whereDate('inscripciones.fechaInscripcion', '<=', $fechaFin)
            ->groupBy('usuarios.nombreUsuario')
            ->orderBy('totalGanado', 'desc')
            ->get();
    }

}
