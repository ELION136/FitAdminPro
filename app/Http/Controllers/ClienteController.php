<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Entrenador;
use App\Models\Asistencia;
use App\Models\Servicio;
use App\Models\Inscripcion;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientesExport;
use Illuminate\Support\Facades\Mail;
class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::all();
        return view('admin.clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('admin.clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        // Validar la solicitud
        $request->validate([
            'telefonoEmergencia' => 'nullable|string|max:15',
            'nombre' => 'required|string|max:50',
            'primerApellido' => 'required|string|max:50',
            'segundoApellido' => 'nullable|string|max:50',
            'fechaNacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Crear el cliente asociado
        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            'primerApellido' => $request->primerApellido,
            'segundoApellido' => $request->segundoApellido,
            'fechaNacimiento' => $request->fechaNacimiento,
            'genero' => $request->genero,
            'telefonoEmergencia' => $request->telefonoEmergencia,
            'fechaCreacion' => now(),
            'idAutor' => $user->idUsuario // Asigna el ID del usuario autenticado
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profile_images', 'public');
            $cliente->image = $path;
            $cliente->save();
        }

        if (!$cliente) {
            return redirect()->back()->with('error', 'Error al crear al cliente');
        }

        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.clientes.index')->with('mensaje', 'El cliente fue registrado correctamente.')->with('icono', 'success');
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('admin.clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        // Obtener el usuario autenticado
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        // Validar la solicitud
        $request->validate([
            'telefonoEmergencia' => 'nullable|string|max:15',
            'nombre' => 'required|string|max:50',
            'primerApellido' => 'required|string|max:50',
            'segundoApellido' => 'nullable|string|max:50',
            'fechaNacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Actualizar los datos del cliente
        $cliente->update([
            'nombre' => $request->nombre,
            'primerApellido' => $request->primerApellido,
            'segundoApellido' => $request->segundoApellido,
            'fechaNacimiento' => $request->fechaNacimiento,
            'genero' => $request->genero,
            'telefonoEmergencia' => $request->telefonoEmergencia,
            'idAutor' => $user->idUsuario, // Asigna el ID del usuario autenticado
        ]);

        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe
            if ($cliente->image) {
                Storage::disk('public')->delete($cliente->image);
            }
            // Guardar la nueva imagen
            $path = $request->file('image')->store('profile_images', 'public');
            $cliente->image = $path;
            $cliente->save();
        }

        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.clientes.index')->with('mensaje', 'El cliente fue actualizado correctamente.')->with('icono', 'success');
    }

    public function destroy(string $id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        $cliente = cliente::findOrFail($id);

        // Actualizar la tabla empleados
        $cliente->update([
            'eliminado' => 0, // Marcando como eliminado
            'idAutor' => $user->idUsuario,
        ]);

        return redirect()->route('admin.clientes.index')->with('mensaje', 'Empleado eliminado con éxito')->with('icono', 'success');
    }
    public function forceDestroy($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        $cliente = Cliente::findOrFail($id);

        // Eliminar el usuario asociado
        $usuario = User::findOrFail($cliente->idUsuario);
        $usuario->delete();

        // Eliminar el empleado
        $cliente->delete();

        return redirect()->route('admin.clientes.index')->with('mensaje', 'Cliente eliminado permanentemente con éxito')->with('icono', 'success');
    }


    public function eliminados()
    {
        $eliminados = Cliente::with('usuario')->where('eliminado', 0)->get();
        return view('admin.clientes.eliminados', compact('eliminados'));
    }



    public function restore($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        $cliente = Cliente::findOrFail($id);

        // Cambiar el estado de 'eliminado' de 0 a 1
        $cliente->update([
            'eliminado' => 1,
            'idAutor' => $user->idUsuario,
        ]);

        // Actualizar la tabla usuarios asociada
        $usuario = User::findOrFail($cliente->idUsuario);
        $usuario->update([
            'eliminado' => 1,
            'idAutor' => $user->idUsuario,
        ]);

        return redirect()->route('admin.clientes.index')->with('mensaje', 'Cliente restaurado con éxito')->with('icono', 'success');
    }

}
