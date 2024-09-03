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
    
    public function create()
    {
        
        $planes = PlanMembresia::where('eliminado', 1)->get();
        $clientes = Cliente::where('eliminado', 1)->get();

        return view('admin.inscripciones.create', compact('planes', 'clientes'));
    }

    
    public function store(Request $request)
    {
    
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

        DB::transaction(function () use ($request, $plan) {
            
            $membresiaExistente = Membresia::where('idCliente', $request->cliente_id)
                                            ->where('estado', 'Activa')
                                            ->first();

            if ($membresiaExistente) {
                
                return redirect()->back()->with('error', 'El cliente ya tiene una membresía activa.');
                //abort(403, 'El cliente ya tiene una membresía activa.');
            }

        
            $membresia = Membresia::create([
                'idCliente' => $request->cliente_id,
                'idPlan' => $request->plan_id,
                'fechaInicio' => $request->fecha_inicio,
                'fechaFin' => Carbon::parse($request->fecha_inicio)->addDays($plan->duracion),
                'estado' => 'Activa',
                'idAutor' => auth()->user()->idUsuario, 
            ]);

            
            Pago::create([
                'idMembresia' => $membresia->idMembresia,
                'monto' => $request->monto_pago,
                'fechaPago' => now(),
                'metodoPago' => $request->metodo_pago,
                'idAutor' => auth()->user()->idUsuario,
            ]);

        
            $cliente = Cliente::find($request->cliente_id);
           // Mail::to($cliente->email)->send(new \App\Mail\InscripcionMembresia($cliente, $membresia));
        });

        return back()->with('success', 'Membresía registrada exitosamente.');

    }
}
