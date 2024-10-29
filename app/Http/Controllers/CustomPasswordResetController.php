<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CustomPasswordResetController extends Controller
{
    // Enviar el enlace de restablecimiento de contraseña
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Generar el token de restablecimiento
            $token = Str::random(60);
            $user->forceFill([
                'password_reset_token' => $token,
                'password_reset_token_expires' => Carbon::now()->addMinutes(60),
            ])->save();

            // Enviar el correo electrónico con el enlace de restablecimiento
            Mail::send('emails.password_reset', ['token' => $token, 'email' => $user->email], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Restablece tu contraseña');
            });

            // Redirigir de vuelta con mensaje de éxito para SweetAlert
            return redirect()->back()->with('status', 'Enlace de restablecimiento enviado. Revisa tu correo.');
        }

        return redirect()->back()->withErrors(['email' => 'No se encontró un usuario con ese correo electrónico.']);
    }

    // Procesar el restablecimiento de contraseña
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::where('email', $request->email)
            ->where('password_reset_token', $request->token)
            ->where('password_reset_token_expires', '>', Carbon::now())
            ->first();

        if ($user) {
            // Actualizar la contraseña y limpiar los campos de token
            $user->password = Hash::make($request->password);
            $user->password_reset_token = null;
            $user->password_reset_token_expires = null;
            $user->save();

            // Redirigir al login con mensaje de éxito
            return redirect()->route('login')->with('status', 'Contraseña restablecida con éxito. Inicia sesión con tu nueva contraseña.');
        }

        return redirect()->back()->withErrors(['token' => 'Token inválido o expirado.']);
    }
}