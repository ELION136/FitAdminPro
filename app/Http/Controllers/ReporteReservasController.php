<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; 
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReservasExport;

class ReporteReservasController extends Controller
{
    public function index(Request $request)
    {
        // Obtenemos los parámetros de filtro
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $nombreCliente = $request->input('nombre_cliente');
        $estado = $request->input('estado');
        $servicioMasReservado = $request->input('servicio_mas_reservado');
        $clienteConMasReservas = $request->input('cliente_con_mas_reservas');

        // Consulta base
        $query = Reserva::query()->with(['cliente', 'detalleReservas.horario.servicio']);

        // Filtros
        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fechaReserva', [$fechaInicio, $fechaFin]);
        }

        if ($nombreCliente) {
            $query->whereHas('cliente', function ($q) use ($nombreCliente) {
                $q->where('nombre', 'like', "%$nombreCliente%");
            });
        }

        if ($estado) {
            $query->where('estado', $estado);
        }

        if ($servicioMasReservado) {
            $query->whereHas('detalleReservas.horario.servicio', function ($q) use ($servicioMasReservado) {
                $q->where('nombre', 'like', "%$servicioMasReservado%");
            });
        }

        // Obtener los totales de pagos por estado
        $reservas = $query->get();
        $totalPagosCompletados = $reservas->where('estado', 'pagado')->sum('total');
        $totalPagosPendientes = $reservas->where('estado', 'pendiente')->sum('total');
        $totalPagosCancelados = $reservas->where('estado', 'cancelado')->sum('total');

        // Totales de reservas
        $totalReservas = $reservas->count();

        return view('admin.reportes.reservas', compact(
            'reservas', 
            'totalPagosCompletados', 
            'totalPagosPendientes', 
            'totalPagosCancelados', 
            'totalReservas'
        ));
    }

    public function exportarPDF(Request $request)
    {
        // Usa el método privado filtrarReservas para obtener las reservas filtradas
        $reservas = $this->filtrarReservas($request);

        // Calcula los totales para incluirlos en el PDF
        $totalReservas = $reservas->count();
        $totalPagosCompletados = $reservas->where('estado', 'pagado')->sum('total');
        $totalPagosPendientes = $reservas->where('estadoPago', 'pendiente')->sum('total');
        $totalPagosCancelados = $reservas->where('estadoPago', 'cancelado')->sum('total');

        // Crea el PDF usando los datos filtrados y los totales
        $pdf = Pdf::loadView('admin.reportes.pdf_reservas', compact(
            'reservas', 
            'totalReservas', 
            'totalPagosCompletados', 
            'totalPagosPendientes', 
            'totalPagosCancelados'
        ));

        // Retorna el archivo PDF para su descarga
        return $pdf->download('reporte_reservas.pdf');
    }

    public function exportarExcel(Request $request)
    {
        $reservas = $this->filtrarReservas($request);

        // Exportar los datos a Excel
        return Excel::download(new ReservasExport($reservas), 'reporte_reservas.xlsx');
    }

    private function filtrarReservas(Request $request)
    {
        $query = Reserva::query()->with(['cliente', 'detalleReservas.horario.servicio']);

        // Aplica los mismos filtros que en el método index
        if ($request->input('fecha_inicio') && $request->input('fecha_fin')) {
            $query->whereBetween('fechaReserva', [$request->input('fecha_inicio'), $request->input('fecha_fin')]);
        }

        if ($request->input('nombre_cliente')) {
            $query->whereHas('cliente', function ($q) use ($request) {
                $q->where('nombre', 'like', "%" . $request->input('nombre_cliente') . "%");
            });
        }

        if ($request->input('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        if ($request->input('servicio_mas_reservado')) {
            $query->whereHas('detalleReservas.horario.servicio', function ($q) use ($request) {
                $q->where('nombre', 'like', "%" . $request->input('servicio_mas_reservado') . "%");
            });
        }

        return $query->get();
    }
}
