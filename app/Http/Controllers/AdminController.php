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
        $users = User::all();
        $userName = Auth::user()->nombreUsuario;
        $totalUsuarios = User::count();
        $totalEntrenadores = \DB::table('entrenadores')->where('eliminado', 1)->count();
        $totalClientes = \DB::table('clientes')->where('eliminado', 1)->count();
        $totalAsistencias = Asistencia::whereMonth('fecha', date('m'))->count();
        $totalMembresiasActivas = \DB::table('inscripciones')
            ->where('estado', 'activa')
            ->count();
        $ingresosMes = \DB::table('pagos')
            ->whereMonth('fechaPago', date('m'))
            ->where('estadoPago', 'completado')
            ->sum('monto');
        $reservasPendientes = \DB::table('reservas')
            ->where('estado', 'pendiente')
            ->count();
        $totalServicios = \DB::table('servicios')->where('eliminado', 1)->count();
        $asistenciasPorSemana = Asistencia::select(DB::raw('WEEK(fecha) as semana, COUNT(*) as total'))
            ->groupBy('semana')
            ->pluck('total', 'semana')->all();

        // Membresías activas por mes (gráfico zoomable)
        $membresiasActivas = DB::table('inscripciones')
            ->select(DB::raw('DATE_FORMAT(fechaInicio, "%Y-%m") as mes, COUNT(*) as total'))
            ->where('estado', 'activa')
            ->groupBy('mes')
            ->pluck('total', 'mes')->all();

        return view('admin.index', compact(
            'totalUsuarios','userName','users',
            'totalEntrenadores',
            'totalClientes',
            'totalAsistencias',
            'totalMembresiasActivas',
            'ingresosMes',
            'reservasPendientes',
            'totalServicios',
            'asistenciasPorSemana',
            'membresiasActivas'
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
