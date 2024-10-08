<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empleado;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        // Validación
        Log::info('Iniciando el proceso de validación para crear un usuario.');
        $validator = Validator::make($request->all(), [
            'nombreUsuario' => 'required|unique:usuarios,nombreUsuario|max:50|regex:/^\S*$/u',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6',
            'rol' => 'required|in:Administrador,Vendedor',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            Log::error('Error en la validación:', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Subir y guardar la imagen si existe
        $imageName = null;
        try {
            Log::info('Procesando la subida de imagen.');
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'profile_images/' . time() . '.' . $image->getClientOriginalExtension();

                // Intentar almacenar la imagen
                if (!Storage::disk('public')->put($imageName, file_get_contents($image))) {
                    throw new Exception('No se pudo almacenar la imagen en el disco.');
                }

                Log::info('Imagen subida correctamente:', ['imageName' => $imageName]);
            }

            // Crear el usuario
            Log::info('Creando el usuario en la base de datos.');
            User::create([
                'nombreUsuario' => $request->nombreUsuario,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'image' => $imageName,
                'rol' => $request->rol,
                'idAutor' => Auth::user()->idUsuario,
                'fechaCreacion' => now(),
            ]);

            Log::info('Usuario creado correctamente.');
            return response()->json(['success' => 'Usuario creado correctamente']);
        } catch (Exception $e) {
            Log::error('Error al guardar el usuario:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Hubo un problema al crear el usuario. Por favor, intente de nuevo.'], 500);
        }
    }

    public function update(Request $request, $idUsuario)
    {
        Log::info('Iniciando el proceso de actualización para el usuario:', ['idUsuario' => $idUsuario]);
        $usuario = User::findOrFail($idUsuario);

        // Validación
        $validator = Validator::make($request->all(), [
            'nombreUsuario' => 'required|unique:usuarios,nombreUsuario,' . $usuario->idUsuario . ',idUsuario|max:50|regex:/^\S*$/u',
            'email' => 'required|email|unique:usuarios,email,' . $usuario->idUsuario . ',idUsuario',
            'rol' => 'required|in:Administrador,Vendedor',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            Log::error('Error en la validación al actualizar el usuario:', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Subir y actualizar la imagen si existe
            if ($request->hasFile('image')) {
                Log::info('Procesando la subida de imagen para actualización.');
                $image = $request->file('image');
                $imageName = 'profile_images/' . time() . '.' . $image->getClientOriginalExtension();

                // Intentar almacenar la imagen
                if (!Storage::disk('public')->put($imageName, file_get_contents($image))) {
                    throw new Exception('No se pudo almacenar la imagen en el disco.');
                }

                Log::info('Imagen subida correctamente para actualización:', ['imageName' => $imageName]);
                $usuario->image = $imageName;
            }

            // Actualizar el usuario
            Log::info('Actualizando los datos del usuario en la base de datos.');
            $usuario->update([
                'nombreUsuario' => $request->nombreUsuario,
                'email' => $request->email,
                'rol' => $request->rol,
                'idAutor' => Auth::user()->idUsuario,
            ]);

            Log::info('Usuario actualizado correctamente.');
            return response()->json(['success' => 'Usuario actualizado correctamente']);
        } catch (Exception $e) {
            Log::error('Error al actualizar el usuario:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Hubo un problema al actualizar el usuario. Por favor, intente de nuevo.'], 500);
        }
    }

    public function destroy($idUsuario)
    {
        Log::info('Iniciando el proceso de eliminación para el usuario:', ['idUsuario' => $idUsuario]);
        try {
            $usuario = User::findOrFail($idUsuario);
            $usuario->update(['eliminado' => 0]);
            Log::info('Usuario eliminado correctamente.');
            return response()->json(['success' => 'Usuario eliminado correctamente']);
        } catch (Exception $e) {
            Log::error('Error al eliminar el usuario:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Hubo un problema al eliminar el usuario. Por favor, intente de nuevo.'], 500);
        }
    }

    public function toggleStatus($idUsuario)
    {
        // Verificar que no se esté intentando inhabilitar al usuario actualmente autenticado
        if ($idUsuario == auth()->id()) {
            return response()->json(['error' => 'No se puede inhabilitar al usuario que inició sesión.'], 403);
        }

        // Encontrar el usuario y cambiar su estado
        $usuario = User::findOrFail($idUsuario);
        $usuario->eliminado = $usuario->eliminado == 1 ? 0 : 1; // Alternar entre activo (1) e inactivo (0)
        $usuario->save();

        return response()->json(['success' => 'Estado del usuario actualizado exitosamente.']);
    }








    public function profile()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    // Actualiza el perfil del usuario autenticado
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validar los datos del request de forma básica
        $request->validate([
            'nombreUsuario' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email,' . $user->idUsuario . ',idUsuario',
            //'telefono' => 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            $user->nombreUsuario = $request->nombreUsuario;
            $user->email = $request->email;
            // $user->telefono = $request->telefono;
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


            DB::commit();

            return redirect()->route('profile.index')->with('success', 'Perfil actualizado con éxito.')->with('icono', 'success');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Hubo un error al actualizar el perfil. Por favor, inténtalo nuevamente.');
        }
    }


    public function validateProfile(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'nombreUsuario' => 'required|string|max:255|unique:usuarios,nombreUsuario,' . $user->idUsuario . ',idUsuario',
            'telefono' => 'nullable|digits_between:7,10',
            'email' => 'required|string|email|max:255|unique:usuarios,email,' . $user->idUsuario . ',idUsuario',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        return response()->json(['success' => 'Validación exitosa']);
    }


}






