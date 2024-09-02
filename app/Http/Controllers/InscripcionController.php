<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membresia;
use App\Models\Cliente;
use App\Models\PlanMembresia;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class InscripcionController extends Controller
{
    // Formulario para inscribir una nueva membresía
    public function create()
    {
        // Obtener todos los planes de membresía disponibles y los clientes
        $planes = PlanMembresia::where('eliminado', 1)->get();
        $clientes = Cliente::where('eliminado', 1)->get();

        return view('admin.inscripciones.create', compact('planes', 'clientes'));
    }

    // Guardar la inscripción y el pago correspondiente
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'cliente_id' => 'required|exists:clientes,idCliente',
            'plan_id' => 'required|exists:planesMembresia,idPlan',
            'fecha_inicio' => 'required|date',
            'monto_pago' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string',
        ]);
        $plan = PlanMembresia::find($request->plan_id);
    
        if ($request->monto_pago != $plan->precio) {
        return redirect()->back()->with('error', 'El monto del pago no coincide con el precio del plan seleccionado.');
        }

        // Iniciar una transacción de base de datos
        DB::transaction(function () use ($request, $plan) {
            // Verificar si el cliente ya tiene una membresía activa
            $membresiaExistente = Membresia::where('idCliente', $request->cliente_id)
                                            ->where('estado', 'Activa')
                                            ->first();

            if ($membresiaExistente) {
                // Lógica para manejar el caso en que el cliente ya tenga una membresía activa
                return redirect()->back()->with('error', 'El cliente ya tiene una membresía activa.');
                //abort(403, 'El cliente ya tiene una membresía activa.');
            }

            // Crear una nueva membresía
            $membresia = Membresia::create([
                'idCliente' => $request->cliente_id,
                'idPlan' => $request->plan_id,
                'fechaInicio' => $request->fecha_inicio,
                'fechaFin' => Carbon::parse($request->fecha_inicio)->addDays($plan->duracion),
                'estado' => 'Activa',
                'idAutor' => auth()->user()->idUsuario, // Asumiendo que hay autenticación
            ]);

            // Registrar el pago correspondiente
            Pago::create([
                'idMembresia' => $membresia->idMembresia,
                'monto' => $request->monto_pago,
                'fechaPago' => now(),
                'metodoPago' => $request->metodo_pago,
                'idAutor' => auth()->user()->idUsuario,
            ]);

            // Enviar notificación por correo electrónico
            $cliente = Cliente::find($request->cliente_id);
           // Mail::to($cliente->email)->send(new \App\Mail\InscripcionMembresia($cliente, $membresia));
        });

        return back()->with('success', 'Membresía registrada exitosamente.');

    }
}
