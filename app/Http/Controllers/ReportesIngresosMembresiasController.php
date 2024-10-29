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
        // Filtros por fecha
        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');

        // Si no se han seleccionado filtros de fecha, devolver una colección vacía
        if (!$request->filled('fechaInicio') || !$request->filled('fechaFin')) {
            $ingresosPorMembresias = collect(); // Colección vacía
            $totalInscripciones = 0;
            $totalGanado = 0;
        } else {
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
                ->when($fechaInicio, function ($query) use ($fechaInicio) {
                    $query->whereDate('inscripciones.fechaInscripcion', '>=', $fechaInicio);
                })
                ->when($fechaFin, function ($query) use ($fechaFin) {
                    $query->whereDate('inscripciones.fechaInscripcion', '<=', $fechaFin);
                })
                ->groupBy('clientes.idCliente', 'membresias.idMembresia', 'usuarios.idUsuario')
                ->orderBy('totalPagado', 'desc')
                ->get();

            // Calcular los totales generales
            $totalInscripciones = $ingresosPorMembresias->count();
            $totalGanado = $ingresosPorMembresias->sum('totalPagado');
        }

        return view('admin.reportes.ingresos-membresias', compact('ingresosPorMembresias', 'totalInscripciones', 'totalGanado', 'fechaInicio', 'fechaFin'));
    }

    // Exportar a PDF
    public function exportarPDF(Request $request)
    {
        $ingresosPorMembresias = $this->filtrarIngresosPorMembresias($request);

        // Capturar las fechas seleccionadas en los filtros
        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');

        // Calcular los totales generales
        $totalInscripciones = $ingresosPorMembresias->sum('totalInscripciones');
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
        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');

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
            ->when($fechaInicio, function ($query) use ($fechaInicio) {
                $query->whereDate('inscripciones.fechaInscripcion', '>=', $fechaInicio);
            })
            ->when($fechaFin, function ($query) use ($fechaFin) {
                $query->whereDate('inscripciones.fechaInscripcion', '<=', $fechaFin);
            })
            ->groupBy('clientes.idCliente', 'membresias.idMembresia', 'usuarios.idUsuario')
            ->orderBy('totalGanado', 'desc')
            ->get();
    }
}
