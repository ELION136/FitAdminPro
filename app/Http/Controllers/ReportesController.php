<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscripcion;
use App\Models\Membresia;
use App\Models\Servicio;
use App\Models\Cliente;
use TCPDF;
use App\Exports\InscripcionesExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportesController extends Controller
{
    // Mostrar la vista de reportes de inscripciones
    // Mostrar la vista de reportes de inscripciones
    public function inscripciones(Request $request)
    {
        // Cargar clientes para los filtros
        $clientes = Cliente::select('idCliente', 'nombre', 'primerApellido')->get();
    
        // Filtros recibidos
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $estado = $request->input('estado');
        $estadoPago = $request->input('estadoPago');
        $clienteNombre = $request->input('cliente_nombre');
    
        // Construcción de la consulta dinámica
        $query = Inscripcion::query();
    
        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fechaInscripcion', [$fechaInicio, $fechaFin]);
        }
    
        if ($estado) {
            $query->where('estado', $estado);
        }
    
        if ($clienteNombre) {
            $query->whereHas('cliente', function ($q) use ($clienteNombre) {
                $q->where('nombre', 'like', '%' . $clienteNombre . '%');
            });
        }
    
        // Obtener los resultados
        $inscripciones = $query->with(['cliente', 'detalleInscripciones.membresia'])->get();
    
        // Cálculos adicionales
        $totalInscripciones = $inscripciones->count();
        $totalIngresos = $inscripciones->sum('totalPago');
    
        return view('admin.reportes.inscripciones', compact('clientes', 'inscripciones', 'totalInscripciones', 'totalIngresos'));
    }
    
    // Exportar PDF
    public function generarPDFInscripciones(Request $request)
    {
        // Procesar filtros (igual que en inscripciones)
        // ...

        // Generar PDF con los datos filtrados
        // ...
    }

    // Exportar Excel
    public function generarExcelInscripciones(Request $request)
    {
        // Procesar filtros (igual que en inscripciones)
        // ...

        // Generar Excel con los datos filtrados
        // ...
    }

    // Generar reporte basado en los filtros aplicados
    public function generarReporteInscripciones(Request $request)
    {
        // Filtros recibidos
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $tipoProducto = $request->input('tipo_producto');
        $estado = $request->input('estado');
        $idCliente = $request->input('cliente');

        // Construcción de la consulta dinámica
        $query = Inscripcion::query();

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fechaInscripcion', [$fechaInicio, $fechaFin]);
        }

        if ($tipoProducto) {
            $query->whereHas('detalleInscripciones', function ($q) use ($tipoProducto) {
                $q->where('tipoProducto', $tipoProducto);
            });
        }

        if ($estado) {
            $query->where('estado', $estado);
        }

        if ($idCliente) {
            $query->where('idCliente', $idCliente);
        }

        // Obtener los resultados
        $inscripciones = $query->with(['cliente', 'detalleInscripciones'])->get();

        // Cálculos adicionales
        $totalInscripciones = $inscripciones->count();
        $totalIngresos = $inscripciones->sum('totalPago');

        // Devolver la respuesta en JSON
        return response()->json([
            'inscripciones' => $inscripciones,
            'totalInscripciones' => $totalInscripciones,
            'totalIngresos' => $totalIngresos
        ]);
    }



}
