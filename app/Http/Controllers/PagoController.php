<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Membresia;
use App\Models\Cliente;
use Barryvdh\DomPDF\Facade\Pdf;

use Dompdf\Dompdf;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        // Obtener las fechas de inicio y fin del request, o usar valores predeterminados
        $fechaInicio = $request->input('fechaInicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fechaFin', now()->endOfMonth()->toDateString());

        // Obtener los pagos dentro del rango de fechas
        $pagos = Pago::whereBetween('fechaPago', [$fechaInicio, $fechaFin])->get();

        // Cargar manualmente las relaciones
        foreach ($pagos as $pago) {
            // Obtener la membresía asociada
            $membresia = Membresia::find($pago->idMembresia);
            $pago->membresia = $membresia;

            // Obtener el cliente asociado a la membresía
            $cliente = Cliente::find($membresia->idCliente);
            $pago->membresia->cliente = $cliente;
        }

        // Retornar la vista con los datos
        return view('admin.pagos.index', compact('pagos', 'fechaInicio', 'fechaFin'));
    }

    /**
     * Generar un reporte en PDF de los pagos filtrados por fecha.
     */
    public function generarReporte(Request $request)
    {
        // Obtener las fechas de inicio y fin del request
        $fechaInicio = $request->input('fechaInicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fechaFin', now()->endOfMonth()->toDateString());

        // Obtener los pagos dentro del rango de fechas
        $pagos = Pago::whereBetween('fechaPago', [$fechaInicio, $fechaFin])->get();

        // Cargar manualmente las relaciones
        foreach ($pagos as $pago) {
            // Obtener la membresía asociada
            $membresia = Membresia::find($pago->idMembresia);
            $pago->membresia = $membresia;

            // Obtener el cliente asociado a la membresía
            $cliente = Cliente::find($membresia->idCliente);
            $pago->membresia->cliente = $cliente;
        }

        // Generar el PDF con los datos
        $pdf = Pdf::loadView('admin.pagos.reporte', compact('pagos', 'fechaInicio', 'fechaFin'));

        // Retornar el PDF para descargar
        return $pdf->download('reporte_pagos_' . $fechaInicio . '_al_' . $fechaFin . '.pdf');
    }
}
