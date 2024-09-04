<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Asistencia;
use App\Models\PlanMembresia;
use App\Models\Membresia;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(){
        $total = User::count(); 
        $userName = Auth::user()->nombreUsuario;
        $totalAsistencias = Asistencia::count();
        $totalPlanes = PlanMembresia::count();
        $clientesConMembresia = Membresia::where('estado', 'activa')->count();
        return view('admin.index', compact('total','userName','totalAsistencias', 'totalPlanes', 'clientesConMembresia'));
        

    } 
    public function home() { 
        // Obtener todos los usuarios
        $users = User::all();
        
        // Retornar la vista 'admin.home' con los usuarios
        return view('admin.home', compact('users'));
    }

        
}
