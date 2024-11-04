<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscripcion;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IngresosServiciosExport;
use DB;

class ReportesIngresosServiciosController extends Controller
{
    // Mostrar la vista principal con filtros
    public function index(Request $request)
    {
        // Definir la fecha actual como predeterminado
        $fechaHoy = Carbon::now()->format('Y-m-d');
        $fechaInicio = $request->input('fechaInicio', $fechaHoy);
        $fechaFin = $request->input('fechaFin', $fechaHoy);

        // Si no se han seleccionado filtros de fecha, devolver una colección vacía
        if (!$request->filled('fechaInicio') || !$request->filled('fechaFin')) {
            $ingresosPorServicios = collect(); // Colección vacía
            $totalInscripciones = 0;
            $totalGanado = 0;
        } else {
            // Consulta para obtener ingresos solo por servicios
            $ingresosPorServicios = Inscripcion::select(
                'clientes.nombre as clienteNombre',
                'clientes.primerApellido as clienteApellido',
                'servicios.nombre as servicioNombre',
                'usuarios.nombreUsuario as vendedor',
                DB::raw('COUNT(inscripciones.idInscripcion) as totalInscripciones'),
                DB::raw('SUM(inscripciones.totalPago) as totalGanado')
            )
                ->join('detalle_inscripciones', 'inscripciones.idInscripcion', '=', 'detalle_inscripciones.idInscripcion')
                ->join('clientes', 'inscripciones.idCliente', '=', 'clientes.idCliente')
                ->join('usuarios', 'inscripciones.idUsuario', '=', 'usuarios.idUsuario')
                ->join('servicios', 'detalle_inscripciones.idServicio', '=', 'servicios.idServicio')
                ->where('detalle_inscripciones.tipoProducto', 'servicio') // Solo servicios
                ->whereDate('inscripciones.fechaInscripcion', '>=', $fechaInicio)
                ->whereDate('inscripciones.fechaInscripcion', '<=', $fechaFin)
                ->groupBy('clientes.idCliente', 'servicios.idServicio', 'usuarios.idUsuario')
                ->orderBy('totalGanado', 'desc')
                ->get();

            // Calcular los totales generales
            $totalInscripciones = $ingresosPorServicios->sum('totalInscripciones');
            $totalGanado = $ingresosPorServicios->sum('totalGanado');
        }

        return view('admin.reportes.ingresos-servicios', compact('ingresosPorServicios', 'totalInscripciones', 'totalGanado', 'fechaInicio', 'fechaFin'));
    }

    // Exportar a PDF
    public function exportarPDF(Request $request)
    {
        // Definir la fecha actual como predeterminado
        $fechaHoy = Carbon::now()->format('Y-m-d');
        $fechaInicio = $request->input('fechaInicio', $fechaHoy);
        $fechaFin = $request->input('fechaFin', $fechaHoy);

        // Obtener los datos filtrados
        $ingresosPorServicios = $this->filtrarIngresosPorServicios($request);

        // Calcular los totales generales
        $totalInscripciones = $ingresosPorServicios->sum('totalInscripciones');
        $totalGanado = $ingresosPorServicios->sum('totalGanado');

        // Cargar la vista y generar el PDF
        $pdf = Pdf::loadView('admin.reportes.ingresos-servicios-pdf', compact('ingresosPorServicios', 'totalInscripciones', 'totalGanado', 'fechaInicio', 'fechaFin'))
            ->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_remote", true);
        $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

        // Establecer la ruta base para las imágenes
        $pdf->getDomPDF()->set_option("chroot", public_path());

        return $pdf->stream('reporte_ingresos_servicios.pdf');
    }

    // Exportar a Excel
    public function exportarExcel(Request $request)
    {
        $ingresosPorServicios = $this->filtrarIngresosPorServicios($request);
        return Excel::download(new IngresosServiciosExport($ingresosPorServicios), 'reporte_ingresos_servicios.xlsx');
    }

    // Filtrar inscripciones por servicios según los parámetros de búsqueda
    private function filtrarIngresosPorServicios(Request $request)
    {
        // Definir la fecha actual como predeterminado
        $fechaHoy = Carbon::now()->format('Y-m-d');
        $fechaInicio = $request->input('fechaInicio', $fechaHoy);
        $fechaFin = $request->input('fechaFin', $fechaHoy);

        return Inscripcion::select(
            'clientes.nombre as clienteNombre',
            'clientes.primerApellido as clienteApellido',
            'servicios.nombre as servicioNombre',
            'usuarios.nombreUsuario as vendedor',
            DB::raw('COUNT(inscripciones.idInscripcion) as totalInscripciones'),
            DB::raw('SUM(inscripciones.totalPago) as totalGanado')
        )
            ->join('detalle_inscripciones', 'inscripciones.idInscripcion', '=', 'detalle_inscripciones.idInscripcion')
            ->join('clientes', 'inscripciones.idCliente', '=', 'clientes.idCliente')
            ->join('usuarios', 'inscripciones.idUsuario', '=', 'usuarios.idUsuario')
            ->join('servicios', 'detalle_inscripciones.idServicio', '=', 'servicios.idServicio')
            ->where('detalle_inscripciones.tipoProducto', 'servicio')
            ->whereDate('inscripciones.fechaInscripcion', '>=', $fechaInicio)
            ->whereDate('inscripciones.fechaInscripcion', '<=', $fechaFin)
            ->groupBy('clientes.idCliente', 'servicios.idServicio', 'usuarios.idUsuario')
            ->orderBy('totalGanado', 'desc')
            ->get();
    }
}
