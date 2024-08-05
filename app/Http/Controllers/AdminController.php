<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(){
        $total = User::count(); 
        $userName = Auth::user()->nombreUsuario;
        return view('admin.index', compact('total','userName'));
        

    } 
    public function home() { 
        // Obtener todos los usuarios
        $users = User::all();
        
        // Retornar la vista 'admin.home' con los usuarios
        return view('admin.home', compact('users'));
    }

        
}
