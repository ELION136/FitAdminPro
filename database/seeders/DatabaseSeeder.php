<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Empleado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
       
         // Sembrar la tabla de Usuarios
         DB::table('usuarios')->insert([
            [
                'nombreUsuario' => 'admin',
                'password' => Hash::make('password'),
                'email' => 'admin@example.com',
                'rol' => 'Administrador',
                'idAutor' => 1
            ],
            [
                'nombreUsuario' => 'empleado1',
                'password' => Hash::make('password'),
                'email' => 'empleado1@example.com',
                'rol' => 'Empleado',
                'idAutor' => 2
            ],
            [
                'nombreUsuario' => 'empleado2',
                'password' => Hash::make('password'),
                'email' => 'empleado2@example.com',
                'rol' => 'Empleado',
                'idAutor' => 3
            ],
            [
                'nombreUsuario' => 'cliente1',
                'password' => Hash::make('password'),
                'email' => 'cliente1@example.com',
                'rol' => 'Cliente',
                'idAutor' => 4
            ],
            [
                'nombreUsuario' => 'cliente2',
                'password' => Hash::make('password'),
                'email' => 'cliente2@example.com',
                'rol' => 'Cliente',
                'idAutor' => 5
            ]
        ]);

        // Sembrar la tabla de Empleados
        DB::table('empleados')->insert([
            [
                'idUsuario' => 2,
                'nombre' => 'Empleado Uno',
                'primerApellido' => 'Apellido1',
                'segundoApellido' => 'Apellido2',
                'fechaNacimiento' => '1990-01-01',
                'genero' => 'Masculino',
                'profesion'=>'Entrenador',
                'especialidad'=>'yoga',
                'fechaContratacion' => '2020-01-01',
                'idAutor' => 2
            ],
            [
                'idUsuario' => 3,
                'nombre' => 'Empleado Dos',
                'primerApellido' => 'Apellido1',
                'segundoApellido' => 'Apellido2',
                'fechaNacimiento' => '1992-02-02',
                'genero' => 'Femenino',
                'profesion'=>'Entrenadora',
                'especialidad'=>'nutriocionista',
                'fechaContratacion' => '2021-02-02',
                'idAutor' => 3
            ]
        ]);

        // Sembrar la tabla de Clientes
        DB::table('clientes')->insert([
            [
                'idUsuario' => 4,
                'nombre' => 'Cliente Uno',
                'primerApellido' => 'Apellido1',
                'segundoApellido' => 'Apellido2',
                'fechaNacimiento' => '1995-03-03',
                'genero' => 'Masculino',
                'idAutor' => 4
            ],
            [
                'idUsuario' => 5,
                'nombre' => 'Cliente Dos',
                'primerApellido' => 'Apellido1',
                'segundoApellido' => 'Apellido2',
                'fechaNacimiento' => '1997-04-04',
                'genero' => 'Femenino',
                'idAutor' => 5
            ]
        ]);
    }
}
