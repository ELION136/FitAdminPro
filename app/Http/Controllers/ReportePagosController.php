<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportacionPagos;

class ReportePagosController extends Controller
{
    public function index()
    {
        return view('admin.reportes.pagos'); // Initial empty view
    }

    public function getPagos(Request $request)
    {
        // Add filtering logic
        $query = Pago::query();
        
        if ($request->has('estadoPago')) {
            $query->where('estadoPago', $request->estadoPago);
        }

        if ($request->has('fechaInicio') && $request->has('fechaFin')) {
            $query->whereBetween('fechaPago', [$request->fechaInicio, $request->fechaFin]);
        }

        return response()->json($query->get());
    }

    public function exportPDF()
    {
        $pagos = Pago::all();
        $pdf = Pdf::loadView('reportes.pagos_pdf', compact('pagos'));
        return $pdf->download('reporte_pagos.pdf');
    }

    public function exportExcel()
    {
        //return Excel::download(new ExportacionPagos, 'reporte_pagos.xlsx');
    }
}
