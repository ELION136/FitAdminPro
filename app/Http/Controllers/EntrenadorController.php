<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrenador;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class EntrenadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entrenadores = Entrenador::with('usuario')->where('eliminado', 1)->get();
        return view('admin.entrenadores.index', compact('entrenadores'));
    }

    public function create()
    {
        return view('admin.entrenadores.create');
    }

    private function generatePassword()
    {
        $prefix = 'fitadminpro';
        $randomNumbers = rand(1000, 9999);
        return $prefix . $randomNumbers;
    }
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
            'telefono' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nombre' => 'required|string|max:50',
            'primerApellido' => 'required|string|max:50',
            'segundoApellido' => 'nullable|string|max:50',
            'fechaNacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'fechaContratacion' => 'required|date',
            'direccion' => 'nullable|string|max:50',
            'especialidad' => 'required',
            'descripcion' => 'nullable|string|max:100',
        ]);
        
        // Generar una contraseña temporal
        $temporaryPassword = $this->generatePassword();


        // Crear el usuario
        $usuario = User::create([
            'nombreUsuario' => $request->nombreUsuario,
            'email' => $request->email,
            'password' => hash::make($temporaryPassword),
            'telefono' => $request->telefono,
            'rol' => 'Entrenador',
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
        $entrenador = Entrenador::create([
            'idUsuario' => $usuario->idUsuario, // Utilizar 'idUsuario' del usuario creado
            'nombre' => $request->nombre,
            'primerApellido' => $request->primerApellido,
            'segundoApellido' => $request->segundoApellido,
            'fechaNacimiento' => $request->fechaNacimiento,
            'genero' => $request->genero,
            'fechaContratacion' => $request->fechaContratacion,
            'direccion' => $request->direccion,
            'especialidad' => $request->especialidad,
            'descripcion'=> $request->descripcion,
            'idAutor' => $user->idUsuario, // Utilizar 'idUsuario' del modelo autenticado
            'fechaCreacion' => now(),
        ]);
    
        // Verificar que el entrenador se ha creado correctamente
        if (!$entrenador) {
            return redirect()->back()->with('error', 'Error al crear el empleado');
        }
    
        // Enviar el correo electrónico con la contraseña temporal
        Mail::to($usuario->email)->send(new \App\Mail\TemporaryPasswordMail($usuario, $temporaryPassword));

        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.entrenadores.index')->with('mensaje', 'El registro fue realizado correctamente y se envió un correo electrónico al empleado.')->with('icono', 'success');
    
    }

    // Mostrar el formulario para editar un empleado
    public function edit($id)
    {
        $entrenador = Entrenador::findOrFail($id);
        $usuario = User::findOrFail($entrenador->idUsuario);
        return view('admin.entrenadores.edit', compact('entrenador', 'usuario'));
    }

    // Actualizar un empleado
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $entrenador = Entrenador::findOrFail($id);
        $request->validate([
            'nombreUsuario' => 'required|unique:usuarios,nombreUsuario,' . $entrenador->idUsuario . ',idUsuario',
            'email' => 'required|email|unique:usuarios,email,' . $entrenador->idUsuario . ',idUsuario',
            'telefono' => 'nullable',
            'nombre' => 'required|string|max:50',
            'primerApellido' => 'required|string|max:50',
            'segundoApellido' => 'nullable|string|max:50',
            'fechaNacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'fechaContratacion' => 'required|date',
            'direccion' => 'nullable|string|max:50',
        ]);

        $usuario = User::findOrFail($entrenador->idUsuario);
        $usuario->update([
            'nombreUsuario' => $request->nombreUsuario,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'idAutor' => $user->idUsuario
        ]);

        $entrenador->update([
            'nombre' => $request->nombre,
            'primerApellido' => $request->primerApellido,
            'segundoApellido' => $request->segundoApellido,
            'fechaNacimiento' => $request->fechaNacimiento,
            'genero' => $request->genero,
            'fechaContratacion' => $request->fechaContratacion,
            'direccion' => $request->direccion,
            'idAutor' => $user->idUsuario
        ]);

        return redirect()->route('admin.entrenadores.index') ->with('mensaje', 'Se actualizó el registro correctamente.')
        ->with('icono', 'success');
    }

    // Eliminación lógica de un empleado
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }
    
        $entrenador = Entrenador::findOrFail($id);
    
        // Actualizar la tabla empleados
        $entrenador->update([
            'eliminado' => 0, // Marcando como eliminado
            'idAutor' => $user->idUsuario,
        ]);
    
        // Actualizar la tabla usuarios
        $usuario = User::findOrFail($entrenador->idUsuario);
        $usuario->update([
            'eliminado' => 0, // Marcando como eliminado
            'idAutor' => $user->idUsuario,
        ]);
    
        return redirect()->route('admin.entrenadores.index')->with('mensaje', 'Entrenador eliminado con éxito')->with('icono', 'success');
    }


    //edicion de perfil del empleado 
    public function profile()
    {
        $user = Auth::user();
        $entrenador = $user->entrenador;
        return view('admin.entrenadores.profile', compact('user', 'entrenador'));
    }

    // Actualiza el perfil del usuario autenticado
    public function updateProfile(Request $request)
    {
    $user = Auth::user();
    $entrenador = $user->entrenador;

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
        'fechaContratacion' => 'required|date',
        'direccion' => 'nullable|string|max:50',
        'especialidad' => 'required',
        'descripcion' => 'nullable|string',
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

        $entrenador->nombre = $request->nombre;
        $entrenador->primerApellido = $request->primerApellido;
        $entrenador->segundoApellido = $request->segundoApellido;
        $entrenador->fechaNacimiento = $request->fechaNacimiento;
        $entrenador->genero = $request->genero;
        $entrenador->fechaContratacion = $request->fechaContratacion;
        $entrenador->direccion = $request->direccion;
        $entrenador->especialidad = $request->especialidad;
        $entrenador->descripcion = $request->descripcion;
        $entrenador->idAutor = Auth::id();

        $entrenador->save();

        DB::commit();

        return redirect()->route('admin.entrenadores.profile')->with('success', 'Perfil actualizado con éxito.')->with('icono', 'success');
    } catch (Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Hubo un error al actualizar el perfil. Por favor, inténtalo nuevamente.');
    }
    }
}
