<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscripcion;
use App\Models\DetalleInscripcion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Membresia;
use App\Models\Servicio;
use App\Models\Cliente;
use App\Exports\ClientesExport;
use TCPDF;
use App\Exports\InscripcionesExport;


class ReportesController extends Controller
{
    // Mostrar la vista de reportes de inscripciones
    // Mostrar la vista de reportes de inscripciones
    // Mostrar el formulario con los filtros y listado de clientes
    public function index(Request $request)
    {
        $clientes = Cliente::query();

        // Cálculo de totales para las cards
        $totalClientes = Cliente::count();
        $totalHombres = Cliente::where('genero', 'Masculino')->count();
        $totalMujeres = Cliente::where('genero', 'Femenino')->count();

        // Si no hay filtros aplicados, mostrar los clientes del día actual
        if (!$request->filled('nombre') && 
            !$request->filled('primerApellido') && 
            !$request->filled('genero') && 
            !$request->filled('fechaCreacionInicio') && 
            !$request->filled('fechaCreacionFin')) {
            
            $fechaActual = Carbon::now()->format('Y-m-d');
            $clientes->whereDate('fechaCreacion', $fechaActual);
            $clientes = $clientes->get();
        }
        // Si hay filtros, aplicarlos
        else {
            if ($request->filled('nombre')) {
                $clientes->where('nombre', 'like', '%' . $request->nombre . '%');
            }

            if ($request->filled('primerApellido')) {
                $clientes->where('primerApellido', 'like', '%' . $request->primerApellido . '%');
            }

            if ($request->filled('genero') && $request->genero != '') {
                $clientes->where('genero', $request->genero);
            }

            if ($request->filled('fechaCreacionInicio') && $request->filled('fechaCreacionFin')) {
                $clientes->whereBetween('fechaCreacion', [$request->fechaCreacionInicio, $request->fechaCreacionFin]);
            }

            $clientes = $clientes->get();
        }

        // Establecer valores por defecto para las fechas en la vista
        $fechaActual = Carbon::now()->format('Y-m-d');
        if (!$request->filled('fechaCreacionInicio')) {
            $request->merge(['fechaCreacionInicio' => $fechaActual]);
        }
        if (!$request->filled('fechaCreacionFin')) {
            $request->merge(['fechaCreacionFin' => $fechaActual]);
        }

        return view('admin.reportes.cliente', compact('clientes', 'totalClientes', 'totalHombres', 'totalMujeres'));
    }
    //error no muestra todos los filtros al darle click en todos los generos 

    // Exportar a PDF usando DomPDF
    public function exportarPDF(Request $request)
    {
        $clientes = $this->filtrarClientes($request);

        // Capturar las fechas para el reporte
        $fechaCreacionInicio = $request->fechaCreacionInicio;
        $fechaCreacionFin = $request->fechaCreacionFin;

        // Generar el PDF en orientación vertical (portrait)
        $pdf = Pdf::loadView('admin.reportes.clientes-pdf', compact('clientes', 'fechaCreacionInicio', 'fechaCreacionFin'))
            ->setPaper('a4', 'portrait'); // Cambiado a 'portrait' para formato vertical
        // Configurar DomPDF para permitir archivos externos
        $pdf->getDomPDF()->set_option("enable_remote", true);
        $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

        // Establecer la ruta base para las imágenes
        $pdf->getDomPDF()->set_option("chroot", public_path());

        // Devolver el PDF para verlo en una nueva pestaña
        return $pdf->stream('reporte_clientes.pdf');
    }

    // Exportar a Excel usando Maatwebsite Excel
    public function exportarExcel(Request $request)
    {
        $clientes = $this->filtrarClientes($request);
        return Excel::download(new ClientesExport($clientes), 'reporte_clientes.xlsx');
    }

    // Función para aplicar los filtros a los clientes
    private function filtrarClientes(Request $request)
    {
        $clientes = Cliente::query();

        if ($request->filled('nombre')) {
            $clientes->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('primerApellido')) {
            $clientes->where('primerApellido', 'like', '%' . $request->primerApellido . '%');
        }

        if ($request->filled('genero') && $request->genero != '') {
            $clientes->where('genero', $request->genero);
        }

        if ($request->filled('fechaCreacionInicio') && $request->filled('fechaCreacionFin')) {
            $clientes->whereBetween('fechaCreacion', [$request->fechaCreacionInicio, $request->fechaCreacionFin]);
        }

        return $clientes->get();
    }


}
