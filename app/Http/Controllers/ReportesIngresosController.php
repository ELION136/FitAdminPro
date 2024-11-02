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
        // Establecer fechas predeterminadas como la fecha actual si no están en el request
        $fechaInicio = $request->input('fechaInicio', Carbon::now()->format('Y-m-d'));
        $fechaFin = $request->input('fechaFin', Carbon::now()->format('Y-m-d'));

        // Consultar los ingresos solo si se aplican filtros
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
            ->when($fechaInicio, function ($query) use ($fechaInicio) {
                $query->whereDate('inscripciones.fechaInscripcion', '>=', $fechaInicio);
            })
            ->when($fechaFin, function ($query) use ($fechaFin) {
                $query->whereDate('inscripciones.fechaInscripcion', '<=', $fechaFin);
            })
            ->get();

        // Calcular el total de ingresos
        $totalIngresos = $ingresos->sum('subtotal');

        return view('admin.reportes.ingresos', compact('ingresos', 'totalIngresos', 'fechaInicio', 'fechaFin'));
    }

    // Exportar a PDF
    public function exportarPDF(Request $request)
    {
        $fechaInicio = $request->input('fechaInicio', Carbon::now()->format('Y-m-d'));
        $fechaFin = $request->input('fechaFin', Carbon::now()->format('Y-m-d'));
        
        $ingresos = $this->filtrarIngresos($request, $fechaInicio, $fechaFin);
        $totalIngresos = $ingresos->sum('subtotal');
        $usuarioNombre = auth()->user()->nombreUsuario;

        $pdf = Pdf::loadView('admin.reportes.ingresos-pdf', compact('ingresos', 'fechaInicio', 'fechaFin', 'usuarioNombre', 'totalIngresos'))
            ->setPaper('a4', 'portrait');
            $dompdf = $pdf->getDompdf();
            $dompdf->set_option('isRemoteEnabled', true);
            $dompdf->set_option('isHtml5ParserEnabled', true);
            $dompdf->set_option('isPhpEnabled', true);
            $dompdf->set_option('isFontSubsettingEnabled', true);
            $dompdf->set_option('chroot', public_path());
            $dompdf->set_option('dpi', '150');
            $dompdf->set_option('defaultFont', 'helvetica');
            $dompdf->set_option('fontHeightRatio', 0.9);
            $dompdf->set_option('enable_css_float', true);
        
        $pdf->output();
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        
        // Agregar número de página en cada página
        $canvas->page_text(520, 820, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));
    
       /* // Agregar una marca de agua en cada página
        $canvas->page_text(
            50, 450, // Coordenadas X, Y para centrar mejor la marca de agua
            "GIMNASIO URBANO", // Texto de la marca de agua
            "Arial", // Especifica la fuente (asegúrate de que esté disponible en DomPDF)
            30, // Tamaño de la fuente reducido
            array(0.9, 0.9, 0.9), // Color gris muy claro para una apariencia más transparente
            20, 20, -30 // Escala y ángulo más sutil (-30 grados)
        );*/

        return $pdf->stream('reporte_ingresos.pdf');
    }

    // Exportar a Excel
    public function exportarExcel(Request $request)
    {
        $fechaInicio = $request->input('fechaInicio', Carbon::now()->format('Y-m-d'));
        $fechaFin = $request->input('fechaFin', Carbon::now()->format('Y-m-d'));

        $ingresos = $this->filtrarIngresos($request, $fechaInicio, $fechaFin);
        return Excel::download(new IngresosExport($ingresos), 'reporte_ingresos.xlsx');
    }

    // Filtrar ingresos según los parámetros de búsqueda
    private function filtrarIngresos(Request $request, $fechaInicio, $fechaFin)
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
            ->whereDate('inscripciones.fechaInscripcion', '>=', $fechaInicio)
            ->whereDate('inscripciones.fechaInscripcion', '<=', $fechaFin)
            ->get();
    }

}
