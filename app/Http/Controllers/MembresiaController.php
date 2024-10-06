<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membresia;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\Auth;

class MembresiaController extends Controller
{

    public function index()
    {
        $planes = Membresia::all();
        return view('admin.planes.index', compact('planes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:50',
            'descripcion' => 'nullable|max:255',
            'duracion' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0.01',
        ]);

        $plan = new Membresia($request->all());
        $plan->idAutor = auth()->id();
        $plan->save();

        return redirect()->route('admin.planes.index')->with('success', 'El plan ha sido creado exitosamente');
    }

    public function update(Request $request, $idMembresia)
    {
        $request->validate([
            'nombre' => 'required|max:50',
            'descripcion' => 'nullable|max:255',
            'duracion' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0.01',
        ]);

        $plan = Membresia::findOrFail($idMembresia);
        $plan->update($request->all());
        $plan->idAutor = auth()->id();
        $plan->save();

        return redirect()->route('admin.planes.index')->with('success', 'El plan ha sido actualizado exitosamente');
    }

    public function destroy($idMembresia)
    {
        $plan = Membresia::findOrFail($idMembresia);
        $plan->eliminado = 0;  // Eliminación lógica
        $plan->save();

        return redirect()->route('admin.planes.index')->with('success', 'El plan ha sido eliminado exitosamente');
    }


    public function historial(Request $request)
    {
        // Obtener todas las membresías con sus relaciones (cliente y plan)
        $query = Membresia::with(['cliente', 'planMembresia']);

        if ($request->has('fecha_inicio') && $request->has('fecha_fin')) {
            $query->whereBetween('fechaInicio', [$request->fecha_inicio, $request->fecha_fin]);
        }

        $membresias = $query->get();

        return view('admin.membresias.historial', compact('membresias'));
    }

    public function generarPDF(Request $request)
    {
        $query = Membresia::with(['cliente', 'planMembresia']);

        if ($request->has('fecha_inicio') && $request->has('fecha_fin')) {
            $query->whereBetween('fechaInicio', [$request->fecha_inicio, $request->fecha_fin]);
        }

        $membresias = $query->get();

        $pdf = Pdf::loadView('admin.membresias.generarPDF', compact('membresias'));
        return $pdf->download('reporte_membresias.pdf');
    }
    public function generarCredencial($id)
    {
        $membresia = Membresia::with(['cliente'])->findOrFail($id);

        $pdf = Pdf::loadView('admin.membresias.generarCredencial', compact('membresia'));
        return $pdf->download('credencial_' . $membresia->cliente->nombre . '.pdf');
    }

    public function indexClinteM()
    {
        // Obtener todas las membresías
        $membresias = Membresia::where('eliminado', 1)->get(); // Solo las activas
        return view('cliente.membresias.index', compact('membresias'));
    }

    public function solicitar(Request $request)
    {
        // Aquí puedes manejar la lógica para solicitar una membresía, como enviar un correo al administrador
        $data = $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email',
            'telefono' => 'required|string',
            'idMembresia' => 'required|integer',
        ]);

        // Ejemplo: enviar un correo o crear un registro en la base de datos

        return redirect()->route('cliente.membresias.index')->with('success', 'Solicitud enviada con éxito.');
    }

    public function indexCredencial()
    {
        // Obtener el cliente logueado
        $cliente = Auth::user()->cliente;

        // Buscar la membresía activa del cliente
        $membresia = Inscripcion::where('idCliente', $cliente->idCliente)
            ->where('estado', 'activa')
            ->with('membresia') 
            ->first();

        // Mostrar la misma vista para ambos casos, pero con diferentes datos
        return view('cliente.membresias.info', compact('membresia'));
    }


    public function imprimirCredencial()
    {
        // Obtener el cliente logueado
        $cliente = Auth::user()->cliente;

        // Buscar la membresía activa del cliente
        $membresia = Inscripcion::where('idCliente', $cliente->idCliente)
            ->where('estado', 'activa')
            ->with('membresia') 
            ->first();

        if (!$membresia) {
            return redirect()->route('cliente.membresias.info')->with('error', 'No tienes una membresía activa.');
        }

        // Generar el PDF de la credencial con los detalles de la membresía
        $pdf = PDF::loadView('cliente.membresias.credencial', compact('cliente', 'membresia'));

        // Descargar o mostrar el PDF
        return $pdf->download('credencial_gimnasio.pdf');
    }
}
