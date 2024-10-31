<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Asistencia;
use App\Models\PlanMembresia;
use App\Models\Membresia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $userName = Auth::user()->nombreUsuario;

        // Usuarios
        $totalUsuarios = User::count();

        // Entrenadores
        $totalEntrenadores = DB::table('entrenadores')->where('eliminado', 1)->count();

        // Clientes
        $totalClientes = DB::table('clientes')->where('eliminado', 1)->count();
        $newClientsThisMonth = DB::table('clientes')
            ->where('eliminado', 1)
            ->whereMonth('fechaCreacion', date('m'))
            ->count();

        // Métricas de Ingresos
        $incomeToday = DB::table('inscripciones')
            ->whereDate('fechaInscripcion', today())
            ->sum('totalPago');

        $incomeThisWeek = DB::table('inscripciones')
            ->whereBetween('fechaInscripcion', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('totalPago');

        $incomeThisMonth = DB::table('inscripciones')
            ->whereMonth('fechaInscripcion', date('m'))
            ->sum('totalPago');

        $totalIncome = DB::table('inscripciones')->sum('totalPago');
        // Ingresos por Servicios y Membresías para Hoy
        $incomeTodayServices = DB::table('inscripciones')
            ->join('detalle_inscripciones', 'inscripciones.idInscripcion', '=', 'detalle_inscripciones.idInscripcion')
            ->whereNotNull('detalle_inscripciones.idServicio')
            ->whereDate('inscripciones.fechaInscripcion', today())
            ->sum('inscripciones.montoPagado');

        $incomeTodayMemberships = DB::table('inscripciones')
            ->join('detalle_inscripciones', 'inscripciones.idInscripcion', '=', 'detalle_inscripciones.idInscripcion')
            ->whereNotNull('detalle_inscripciones.idMembresia')
            ->whereDate('inscripciones.fechaInscripcion', today())
            ->sum('inscripciones.montoPagado');

        // Ingresos por Servicios y Membresías para Esta Semana
        $incomeThisWeekServices = DB::table('inscripciones')
            ->join('detalle_inscripciones', 'inscripciones.idInscripcion', '=', 'detalle_inscripciones.idInscripcion')
            ->whereNotNull('detalle_inscripciones.idServicio')
            ->whereBetween('inscripciones.fechaInscripcion', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('inscripciones.montoPagado');

        $incomeThisWeekMemberships = DB::table('inscripciones')
            ->join('detalle_inscripciones', 'inscripciones.idInscripcion', '=', 'detalle_inscripciones.idInscripcion')
            ->whereNotNull('detalle_inscripciones.idMembresia')
            ->whereBetween('inscripciones.fechaInscripcion', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('inscripciones.montoPagado');

        // Ingresos por Servicios y Membresías para Este Mes
        $incomeThisMonthServices = DB::table('inscripciones')
            ->join('detalle_inscripciones', 'inscripciones.idInscripcion', '=', 'detalle_inscripciones.idInscripcion')
            ->whereNotNull('detalle_inscripciones.idServicio')
            ->whereMonth('inscripciones.fechaInscripcion', date('m'))
            ->sum('inscripciones.totalPago');

        $incomeThisMonthMemberships = DB::table('inscripciones')
            ->join('detalle_inscripciones', 'inscripciones.idInscripcion', '=', 'detalle_inscripciones.idInscripcion')
            ->whereNotNull('detalle_inscripciones.idMembresia')
            ->whereMonth('inscripciones.fechaInscripcion', date('m'))
            ->sum('inscripciones.totalPago');

        // Ingresos por Servicios y Membresías para Este Año
        $incomeThisYearServices = DB::table('inscripciones')
            ->join('detalle_inscripciones', 'inscripciones.idInscripcion', '=', 'detalle_inscripciones.idInscripcion')
            ->whereNotNull('detalle_inscripciones.idServicio')
            ->whereYear('inscripciones.fechaInscripcion', date('Y'))
            ->sum('inscripciones.montoPagado');

        $incomeThisYearMemberships = DB::table('inscripciones')
            ->join('detalle_inscripciones', 'inscripciones.idInscripcion', '=', 'detalle_inscripciones.idInscripcion')
            ->whereNotNull('detalle_inscripciones.idMembresia')
            ->whereYear('inscripciones.fechaInscripcion', date('Y'))
            ->sum('inscripciones.montoPagado');


        // Métricas de Membresías
        $totalMembresiasActivas = DB::table('inscripciones')
            ->where('estado', 'activa')
            ->count();

        $expiringMembershipsList = DB::table('inscripciones')
            ->join('clientes', 'inscripciones.idCliente', '=', 'clientes.idCliente')
            ->select('clientes.nombre as nombreCliente', 'inscripciones.fechaInscripcion', 'inscripciones.idInscripcion')
            ->where('inscripciones.estado', 'activa')
            ->whereBetween('inscripciones.fechaInscripcion', [now()->subDays(30), now()])
            ->get();

        // Membresías Más Adquiridas
        $popularMemberships = DB::table('detalle_inscripciones')
            ->join('membresias', 'detalle_inscripciones.idMembresia', '=', 'membresias.idMembresia')
            ->select('membresias.nombre', DB::raw('COUNT(*) as total'))
            ->whereNotNull('detalle_inscripciones.idMembresia')
            ->groupBy('membresias.nombre')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // Servicios Más Solicitados
        $popularServices = DB::table('detalle_inscripciones')
            ->join('servicios', 'detalle_inscripciones.idServicio', '=', 'servicios.idServicio')
            ->select('servicios.nombre', DB::raw('COUNT(*) as total'))
            ->whereNotNull('detalle_inscripciones.idServicio')
            ->groupBy('servicios.nombre')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // Métricas de Asistencias
        $checkInsToday = DB::table('asistencias')
            ->whereDate('fechaAsistencia', today())
            ->count();

        $attendanceThisWeek = DB::table('asistencias')
            ->select(DB::raw('DATE(fechaAsistencia) as date'), DB::raw('COUNT(*) as total'))
            ->whereBetween('fechaAsistencia', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy('date')
            ->get();

        // Preparar datos para ECharts
        $incomeOverTime = DB::table('inscripciones')
            ->select(DB::raw('DATE(fechaInscripcion) as date'), DB::raw('SUM(montoPagado) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->take(30) // Últimos 30 días
            ->get();

        $incomeDates = $incomeOverTime->pluck('date');
        $incomeValues = $incomeOverTime->pluck('total');

        return view('admin.index', compact(
            'userName',
            'totalUsuarios',
            'totalEntrenadores',
            'totalClientes',
            'newClientsThisMonth',
            'incomeToday',
            'incomeThisWeek',
            'incomeThisMonth',
            'totalIncome',
            'totalMembresiasActivas',
            'expiringMembershipsList',
            'popularMemberships',
            'popularServices',
            'checkInsToday',
            'attendanceThisWeek',
            'incomeDates',
            'incomeValues',
            'incomeTodayServices',
            'incomeTodayMemberships',
            'incomeThisWeekServices',
            'incomeThisWeekMemberships',
            'incomeThisMonthServices',
            'incomeThisMonthMemberships',
            'incomeThisYearServices',
            'incomeThisYearMemberships'
        ));
    }
    public function home()
    {
        // Obtener todos los usuarios
        $users = User::all();


        // Retornar la vista 'admin.home' con los usuarios
        return view('admin.home', compact('users'));
    }


}
