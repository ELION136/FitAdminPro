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
        // Método index vacío
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
            'telefono' => 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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






