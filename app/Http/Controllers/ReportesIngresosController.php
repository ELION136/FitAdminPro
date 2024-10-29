<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleInscripcion;
use App\Models\Inscripcion;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IngresosExport;
use Illuminate\Support\Facades\DB;

class ReportesIngresosController extends Controller
{
    public function index(Request $request)
    {
        // Inicialmente, la tabla estará vacía
        $ingresos = collect();

        // Si se aplica algún filtro, ejecutar la consulta
        if ($request->filled('fechaInicio') || $request->filled('fechaFin')) {
            $ingresos = DetalleInscripcion::query()
                ->join('inscripciones', 'detalle_inscripciones.idInscripcion', '=', 'inscripciones.idInscripcion')
                ->join('clientes', 'inscripciones.idCliente', '=', 'clientes.idCliente')
                ->join('usuarios', 'inscripciones.idUsuario', '=', 'usuarios.idUsuario')
                ->select(
                    'inscripciones.fechaInscripcion',
                    'clientes.nombre as clienteNombre',
                    'detalle_inscripciones.tipoProducto',
                    'detalle_inscripciones.precio',
                    'detalle_inscripciones.descuento',
                    DB::raw('(detalle_inscripciones.precio - detalle_inscripciones.descuento) as subtotal'),
                    'usuarios.nombreUsuario as vendedor'
                )
                ->when($request->filled('fechaInicio'), function ($query) use ($request) {
                    $query->whereDate('inscripciones.fechaInscripcion', '>=', $request->fechaInicio);
                })
                ->when($request->filled('fechaFin'), function ($query) use ($request) {
                    $query->whereDate('inscripciones.fechaInscripcion', '<=', $request->fechaFin);
                })
                ->get();
        }

        // Calcular el total de ingresos si hay resultados
        $totalIngresos = $ingresos->sum('subtotal');

        return view('admin.reportes.ingresos', compact('ingresos', 'totalIngresos'));
    }

    // Exportar a PDF
    public function exportarPDF(Request $request)
    {
        $ingresos = $this->filtrarIngresos($request);
        $totalIngresos = $ingresos->sum('subtotal'); // Calcula el total de ingresos
        $usuarioNombre = auth()->user()->nombreUsuario; // Usuario que genera el reporte
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;

        $pdf = Pdf::loadView('admin.reportes.ingresos-pdf', compact('ingresos', 'fechaInicio', 'fechaFin', 'usuarioNombre', 'totalIngresos'))
            ->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_remote", true);
        $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

        // Establecer la ruta base para las imágenes
        $pdf->getDomPDF()->set_option("chroot", public_path());
        return $pdf->stream('reporte_ingresos.pdf');
    }


    // Exportar a Excel
    public function exportarExcel(Request $request)
    {
        $ingresos = $this->filtrarIngresos($request);
        return Excel::download(new IngresosExport($ingresos), 'reporte_ingresos.xlsx');
    }

    // Filtrar ingresos según los parámetros de búsqueda
    private function filtrarIngresos(Request $request)
    {
        return DetalleInscripcion::query()
            ->join('inscripciones', 'detalle_inscripciones.idInscripcion', '=', 'inscripciones.idInscripcion')
            ->join('clientes', 'inscripciones.idCliente', '=', 'clientes.idCliente')
            ->join('usuarios', 'inscripciones.idUsuario', '=', 'usuarios.idUsuario')
            ->select(
                'inscripciones.fechaInscripcion',
                'clientes.nombre as clienteNombre',
                'detalle_inscripciones.tipoProducto',
                'detalle_inscripciones.precio',
                'detalle_inscripciones.descuento',
                DB::raw('(detalle_inscripciones.precio - detalle_inscripciones.descuento) as subtotal'),
                'usuarios.nombreUsuario as vendedor'
            )
            ->when($request->filled('fechaInicio'), function ($query) use ($request) {
                $query->whereDate('inscripciones.fechaInscripcion', '>=', $request->fechaInicio);
            })
            ->when($request->filled('fechaFin'), function ($query) use ($request) {
                $query->whereDate('inscripciones.fechaInscripcion', '<=', $request->fechaFin);
            })
            ->get();
    }

}
