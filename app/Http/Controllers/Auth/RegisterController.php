<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombreUsuario' => ['required', 'string', 'max:50', 'unique:usuarios'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:usuarios'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nombre' => ['required', 'string', 'max:50'],
            'primerApellido' => ['required', 'string', 'max:50'],
            'segundoApellido' => ['nullable', 'string', 'max:50'],
            'fechaNacimiento' => ['required', 'date'],
            'genero' => ['required', 'in:Masculino,Femenino,Otro'],
            'telefono' => ['nullable', 'string', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $usuario = User::create([
                'nombreUsuario' => $data['nombreUsuario'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'telefono' => $data['telefono'] ?? null,
                'rol' => 'Cliente',
                'eliminado' => 1,
            ]);

            Cliente::create([
                'idUsuario' => $usuario->idUsuario,
                'nombre' => $data['nombre'],
                'primerApellido' => $data['primerApellido'],
                'segundoApellido' => $data['segundoApellido'] ?? null,
                'fechaNacimiento' => $data['fechaNacimiento'],
                'genero' => $data['genero'],
                'eliminado' => 1,
            ]);

            return $usuario;
        });
    }
}
