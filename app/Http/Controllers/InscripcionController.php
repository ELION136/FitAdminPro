<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membresia;
use App\Models\Cliente;
use App\Models\PlanMembresia;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class InscripcionController extends Controller
{

    public function create()
    {

        $planes = PlanMembresia::where('eliminado', 1)->get();
        $clientes = Cliente::where('eliminado', 1)->get();

        return view('admin.inscripciones.create', compact('planes', 'clientes'));
    }

    private function generatePassword()
    {
        $prefix = 'fitadminpro';
        $randomNumbers = rand(1000, 9999);
        return $prefix . $randomNumbers;
    }

    public function store(Request $request)
{
    $request->validate([
        'cliente_id' => 'nullable|exists:clientes,idCliente',
        'plan_id' => 'required|exists:planesMembresia,idPlan',
        'fecha_inicio' => 'required|date|after_or_equal:today',
        'metodo_pago' => 'required|string',
    ]);

    if (!$request->cliente_id) {
        // Validación adicional para un nuevo cliente
        $request->validate([
            'nombre' => 'required|string|max:255',
            'primerApellido' => 'required|string|max:255',
            'segundoApellido' => 'nullable|string|max:255',
            'fechaNacimiento' => 'required|date',
            'genero' => 'required|string|in:Masculino,Femenino,Otro',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'nombreUsuario' => 'required|string|max:255|unique:usuarios,nombreUsuario',
        ]);
    }

    $plan = PlanMembresia::find($request->plan_id);

    if ($request->monto_pago != $plan->precio) {
        return redirect()->back()->with('error', 'El monto del pago no coincide con el precio del plan seleccionado.');
    }

    DB::transaction(function () use ($request, $plan) {
        $nuevoCliente = false;
        $temporaryPassword = null;

        if (!$request->filled('cliente_id')) {
            // Generar contraseña automáticamente
            $temporaryPassword = $this->generatePassword();

            // Crear un nuevo usuario
            $usuario = User::create([
                'nombreUsuario' => $request->nombreUsuario, // Asignar el nombre de usuario del request
                'email' => $request->email,
                'password' => hash::make($temporaryPassword),
                'rol' => 'Cliente',
                'idAutor' => auth()->user()->idUsuario,
            ]);

            // Crear un nuevo cliente
            $cliente = Cliente::create([
                'idUsuario' => $usuario->idUsuario,
                'nombre' => $request->nombre,
                'primerApellido' => $request->primerApellido,
                'segundoApellido' => $request->filled('segundoApellido') ? $request->segundoApellido : '',
                'fechaNacimiento' => $request->fechaNacimiento,
                'genero' => $request->genero,
                'direccion' => $request->direccion,
                'idAutor' => auth()->user()->idUsuario,
            ]);

            $cliente_id = $cliente->idCliente;
            $nuevoCliente = true; // Indicar que se ha creado un nuevo cliente
        } else {
            // Cliente ya existente
            $cliente_id = $request->cliente_id;
            $usuario = Cliente::find($cliente_id)->usuario;
        }

        // Verificar si el cliente ya tiene una membresía activa
        $membresiaExistente = Membresia::where('idCliente', $cliente_id)
            ->where('estado', 'Activa')
            ->first();

        if ($membresiaExistente) {
            return redirect()->back()->with('error', 'El cliente ya tiene una membresía activa.');
        }

        // Crear la nueva membresía
        $membresia = Membresia::create([
            'idCliente' => $cliente_id,
            'idPlan' => $request->plan_id,
            'fechaInicio' => $request->fecha_inicio,
            'fechaFin' => Carbon::parse($request->fecha_inicio)->addDays($plan->duracion),
            'estado' => 'Activa',
            'idAutor' => auth()->user()->idUsuario,
        ]);

        // Registrar el pago correspondiente
        Pago::create([
            'idMembresia' => $membresia->idMembresia,
            'monto' => $request->monto_pago,
            'fechaPago' => now(),
            'metodoPago' => $request->metodo_pago,
            'idAutor' => auth()->user()->idUsuario,
        ]);

        $membresia->load('planMembresia');

        // Enviar correo solo si se creó un nuevo cliente
        if ($nuevoCliente) {
            Mail::to($usuario->email)->send(new \App\Mail\TemporaryMemberPasswordMail($usuario, $temporaryPassword, $membresia));
        }
    });

    return back()->with('success', 'Membresía registrada exitosamente.');
}
}
