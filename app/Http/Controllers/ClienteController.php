<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;
class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::with('usuario')->where('eliminado', 1)->get();
        return view('admin.clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
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
            'nombreUsuario' => 'required|unique:usuarios,nombreUsuario',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required',
            'telefono' => 'nullable',
            'image' => 'nullable|image',
            'nombre' => 'required',
            'primerApellido' => 'required',
            'segundoApellido' => 'nullable',
            'fechaNacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino,Otro',
        ]);
    
        // Crear el usuario
        $usuario = User::create([
            'nombreUsuario' => $request->nombreUsuario,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'telefono' => $request->telefono,
            'rol' => 'Cliente',
            'idAutor' => $user->idUsuario, // Utilizar 'idUsuario' del modelo autenticado
            'fechaCreacion' => now(),
        ]);
    
        // Verificar que el usuario se ha creado correctamente
        if (!$usuario) {
            return redirect()->back()->with('error', 'Error al crear el usuario');
        }
    
        // Verificar que el ID del usuario no es null
        if (is_null($usuario->idUsuario)) {
            return redirect()->back()->with('error', 'El ID del usuario es null');
        }
    
        // Subir y guardar la imagen si existe
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put($imageName, file_get_contents($image));
            $usuario->image = $imageName;
            $usuario->save();
        }
    
        // Crear el empleado asociado
        $cliente = Cliente::create([
            'idUsuario' => $usuario->idUsuario, // Utilizar 'idUsuario' del usuario creado
            'nombre' => $request->nombre,
            'primerApellido' => $request->primerApellido,
            'segundoApellido' => $request->segundoApellido,
            'fechaNacimiento' => $request->fechaNacimiento,
            'genero' => $request->genero,
            'idAutor' => $user->idUsuario, // Utilizar 'idUsuario' del modelo autenticado
            'fechaCreacion' => now(),
        ]);
    
        // Verificar que el empleado se ha creado correctamente
        if (!$cliente) {
            return redirect()->back()->with('error', 'Error al crear el empleado');
        }
    
        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.clientes.index')->with('mensaje', 'El registro fue realizado correctamente.')
        ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $cliente = Cliente::findOrFail($id);
        $usuario = User::findOrFail($cliente->idUsuario);
        return view('admin.clientes.edit', compact('cliente', 'usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $cliente = Cliente::findOrFail($id);
        $request->validate([
            'nombreUsuario' => 'required|unique:usuarios,nombreUsuario,' . $cliente->idUsuario . ',idUsuario',
            'email' => 'required|email|unique:usuarios,email,' . $cliente->idUsuario . ',idUsuario',
            'telefono' => 'nullable',
            'nombre' => 'required',
            'primerApellido' => 'required',
            'segundoApellido' => 'nullable',
            'fechaNacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino,Otro',
        ]);

        $usuario = User::findOrFail($cliente->idUsuario);
        $usuario->update([
            'nombreUsuario' => $request->nombreUsuario,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'idAutor' => $user->idUsuario
        ]);

        $cliente->update([
            'nombre' => $request->nombre,
            'primerApellido' => $request->primerApellido,
            'segundoApellido' => $request->segundoApellido,
            'fechaNacimiento' => $request->fechaNacimiento,
            'genero' => $request->genero,
            'idAutor' => $user->idUsuario
        ]);

        return redirect()->route('admin.clientes.index') ->with('mensaje', 'Se actualizó el registro correctamente.')
        ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
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
    
        // Actualizar la tabla usuarios
        $usuario = User::findOrFail($cliente->idUsuario);
        $usuario->update([
            'eliminado' => 0, // Marcando como eliminado
            'idAutor' => $user->idUsuario,
        ]);
    
        return redirect()->route('admin.clientes.index')->with('mensaje', 'Empleado eliminado con éxito')->with('icono', 'success');
    }



    //para el perfil del cliente 

    public function profile()
    {
        $user = Auth::user();
        $cliente = $user->cliente;
        return view('admin.clientes.profile', compact('user', 'cliente'));
    }

    // Actualiza el perfil del usuario autenticado
    public function updateProfile(Request $request)
    {
    $user = Auth::user();
    $cliente = $user->cliente;

    // Validar los datos del request de forma básica
    $request->validate([
        'nombreUsuario' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:usuarios,email,' . $user->idUsuario . ',idUsuario',
        'telefono' => 'nullable|string|max:15',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'nombre' => 'required|string|max:50',
        'primerApellido' => 'required|string|max:50',
        'segundoApellido' => 'nullable|string|max:50',
        'fechaNacimiento' => 'required|date',
        'genero' => 'required|in:Masculino,Femenino,Otro',
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    DB::beginTransaction();

    try {
        $user->nombreUsuario = $request->nombreUsuario;
        $user->email = $request->email;
        $user->telefono = $request->telefono;
        $user->idAutor = Auth::id();

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $path = $request->file('image')->store('profile_images', 'public');
            $user->image = $path;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $cliente->nombre = $request->nombre;
        $cliente->primerApellido = $request->primerApellido;
        $cliente->segundoApellido = $request->segundoApellido;
        $cliente->fechaNacimiento = $request->fechaNacimiento;
        $cliente->genero = $request->genero;
        $cliente->idAutor = Auth::id();

        $cliente->save();

        DB::commit();

        return redirect()->route('admin.clientes.profile')->with('success', 'Perfil actualizado con éxito.')->with('icono', 'success');
    } catch (Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Hubo un error al actualizar el perfil. Por favor, inténtalo nuevamente.');
    }
    }

}
