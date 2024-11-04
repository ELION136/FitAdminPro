<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Entrenador;
use App\Models\Asistencia;
use App\Models\Servicio;
use App\Models\Inscripcion;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
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
        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'telefonoEmergencia' => [
                'nullable',
                'string',
                'max:15',
                'regex:/^[0-9+\-\s]+$/', // Solo permite números, espacios y símbolos + o -
            ],
            'nombre' => [
                'required',
                'string',
                'min:2', // Longitud mínima de 2 caracteres
                'max:50',
                'regex:/^[\pL\s\-]+$/u', // Solo permite letras, espacios y guiones
            ],
            'primerApellido' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[\pL\s\-]+$/u', // Solo permite letras, espacios y guiones
            ],
            'segundoApellido' => [
                'nullable',
                'string',
                'min:2',
                'max:50',
                'regex:/^[\pL\s\-]+$/u', // Solo permite letras, espacios y guiones
            ],
            'fechaNacimiento' => [
                'required',
                'date',
                'before:today', // Asegura que sea una fecha pasada
                'after:1900-01-01', // Opcional: asegura que la fecha sea razonable
            ],
            'genero' => [
                'required',
                'in:Masculino,Femenino,Otro',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048', // Máximo tamaño de archivo en KB
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000', // Dimensiones mínimas y máximas
            ],
        ]);
        // Si la validación falla, retorna los errores en formato JSON
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Obtener el usuario autenticado
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        // Crear el cliente
        $cliente = new Cliente([
            'nombre' => $request->nombre,
            'primerApellido' => $request->primerApellido,
            'segundoApellido' => $request->segundoApellido,
            'fechaNacimiento' => $request->fechaNacimiento,
            'genero' => $request->genero,
            'telefonoEmergencia' => $request->telefonoEmergencia,
            'fechaCreacion' => now(),
            'idAutor' => $user->idUsuario
        ]);

        // Guardar imagen si se proporciona
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profile_images', 'public');
            $cliente->image = $path;
        }

        if ($cliente->save()) {
            // Respuesta de éxito en formato JSON
            return response()->json([
                'success' => true,
                'message' => 'El cliente fue registrado correctamente.'
            ]);
        } else {
            // Respuesta de error si la creación falla
            return response()->json([
                'success' => false,
                'message' => 'Error al crear al cliente'
            ], 500);
        }
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
        // Validar la solicitud con los mensajes de error personalizados
        $validator = Validator::make($request->all(), [
            'telefonoEmergencia' => 'nullable|string|max:15|regex:/^[0-9+\-\s]+$/',
            'nombre' => 'required|string|max:50|regex:/^[\pL\s\-]+$/u',
            'primerApellido' => 'required|string|max:50|regex:/^[\pL\s\-]+$/u',
            'segundoApellido' => 'nullable|string|max:50|regex:/^[\pL\s\-]+$/u',
            'fechaNacimiento' => 'required|date|before:today',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cliente = Cliente::findOrFail($id);
        
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        // Actualizar los datos del cliente
        $cliente->update([
            'nombre' => $request->nombre,
            'primerApellido' => $request->primerApellido,
            'segundoApellido' => $request->segundoApellido,
            'fechaNacimiento' => $request->fechaNacimiento,
            'genero' => $request->genero,
            'telefonoEmergencia' => $request->telefonoEmergencia,
            'idAutor' => $user->idUsuario,
        ]);

        if ($request->hasFile('image')) {
            if ($cliente->image) {
                Storage::disk('public')->delete($cliente->image);
            }
            $path = $request->file('image')->store('profile_images', 'public');
            $cliente->image = $path;
            $cliente->save();
        }

        return response()->json(['success' => true, 'message' => 'Cliente actualizado correctamente']);
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
