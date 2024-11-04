<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscripcion;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IngresosMembresiasExport;
use DB;

class ReportesIngresosMembresiasController extends Controller
{
    // Mostrar la vista principal con filtros
    public function index(Request $request)
    {
        // Definir la fecha actual como predeterminado
        $fechaHoy = Carbon::now()->format('Y-m-d');
        $fechaInicio = $request->input('fechaInicio', $fechaHoy);
        $fechaFin = $request->input('fechaFin', $fechaHoy);

        // Consulta para obtener ingresos solo por membresías
        $ingresosPorMembresias = Inscripcion::select(
            'clientes.nombre as clienteNombre',
            'clientes.primerApellido as clienteApellido',
            'membresias.nombre as membresiaNombre',
            'usuarios.nombreUsuario as vendedor',
            DB::raw('SUM(detalle_inscripciones.precio) as totalPagado')  // Total pagado por cada membresía
        )
            ->join('detalle_inscripciones', 'inscripciones.idInscripcion', '=', 'detalle_inscripciones.idInscripcion')
            ->join('clientes', 'inscripciones.idCliente', '=', 'clientes.idCliente')
            ->join('usuarios', 'inscripciones.idUsuario', '=', 'usuarios.idUsuario')
            ->join('membresias', 'detalle_inscripciones.idMembresia', '=', 'membresias.idMembresia')
            ->where('detalle_inscripciones.tipoProducto', 'membresia') // Solo membresías
            ->whereDate('inscripciones.fechaInscripcion', '>=', $fechaInicio)
            ->whereDate('inscripciones.fechaInscripcion', '<=', $fechaFin)
            ->groupBy('clientes.idCliente', 'membresias.idMembresia', 'usuarios.idUsuario')
            ->orderBy('totalPagado', 'desc')
            ->get();

        // Calcular los totales generales
        $totalInscripciones = $ingresosPorMembresias->count();
        $totalGanado = $ingresosPorMembresias->sum('totalPagado');

        return view('admin.reportes.ingresos-membresias', compact('ingresosPorMembresias', 'totalInscripciones', 'totalGanado', 'fechaInicio', 'fechaFin'));
    }

    // Exportar a PDF
    public function exportarPDF(Request $request)
    {
        // Definir la fecha actual como predeterminado
        $fechaHoy = Carbon::now()->format('Y-m-d');
        $fechaInicio = $request->input('fechaInicio', $fechaHoy);
        $fechaFin = $request->input('fechaFin', $fechaHoy);

        // Obtener los datos filtrados
        $ingresosPorMembresias = $this->filtrarIngresosPorMembresias($request);

        // Calcular los totales generales
        $totalInscripciones = $ingresosPorMembresias->count();
        $totalGanado = $ingresosPorMembresias->sum('totalGanado');

        // Enviar las variables necesarias a la vista
        $pdf = Pdf::loadView('admin.reportes.ingresos-membresias-pdf', compact('ingresosPorMembresias', 'totalInscripciones', 'totalGanado', 'fechaInicio', 'fechaFin'))
            ->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_remote", true);
        $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

        // Establecer la ruta base para las imágenes
        $pdf->getDomPDF()->set_option("chroot", public_path());

        return $pdf->stream('reporte_ingresos_membresias.pdf');
    }

    // Exportar a Excel
    public function exportarExcel(Request $request)
    {
        $ingresosPorMembresias = $this->filtrarIngresosPorMembresias($request);
        return Excel::download(new IngresosMembresiasExport($ingresosPorMembresias), 'reporte_ingresos_membresias.xlsx');
    }

    // Filtrar inscripciones por membresías según los parámetros de búsqueda
    private function filtrarIngresosPorMembresias(Request $request)
    {
        // Definir la fecha actual como predeterminado
        $fechaHoy = Carbon::now()->format('Y-m-d');
        $fechaInicio = $request->input('fechaInicio', $fechaHoy);
        $fechaFin = $request->input('fechaFin', $fechaHoy);

        return Inscripcion::select(
            'clientes.nombre as clienteNombre',
            'clientes.primerApellido as clienteApellido',
            'membresias.nombre as membresiaNombre',
            'usuarios.nombreUsuario as vendedor',
            DB::raw('SUM(detalle_inscripciones.precio) as totalGanado')  // Suma del total pagado por membresías
        )
            ->join('detalle_inscripciones', 'inscripciones.idInscripcion', '=', 'detalle_inscripciones.idInscripcion')
            ->join('clientes', 'inscripciones.idCliente', '=', 'clientes.idCliente')
            ->join('usuarios', 'inscripciones.idUsuario', '=', 'usuarios.idUsuario')
            ->join('membresias', 'detalle_inscripciones.idMembresia', '=', 'membresias.idMembresia')
            ->where('detalle_inscripciones.tipoProducto', 'membresia')
            ->whereDate('inscripciones.fechaInscripcion', '>=', $fechaInicio)
            ->whereDate('inscripciones.fechaInscripcion', '<=', $fechaFin)
            ->groupBy('clientes.idCliente', 'membresias.idMembresia', 'usuarios.idUsuario')
            ->orderBy('totalGanado', 'desc')
            ->get();
    }       
}
