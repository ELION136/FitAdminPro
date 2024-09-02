<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Empleado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\PlanMembresia;
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

        // Crear Usuario Administrador
        DB::table('usuarios')->insert([
            [
                'nombreUsuario' => 'admin',
                'password' => Hash::make('password1'),
                'email' => 'admin@gmail.com',
                'telefono' => '123456789',
                'rol' => 'Administrador',
                'idAutor' => null,
                'eliminado' => 1
            ],
            [
                'nombreUsuario' => 'entrenador1',
                'password' => Hash::make('password2'),
                'email' => 'entrenador1@example.com',
                'telefono' => '987654321',
                'rol' => 'Entrenador',
                'idAutor' => 1,
                'eliminado' => 1
            ],
            [
                'nombreUsuario' => 'cliente1',
                'password' => Hash::make('password3'),
                'email' => 'cliente1@example.com',
                'telefono' => '1122334455',
                'rol' => 'Cliente',
                'idAutor' => 1,
                'eliminado' => 1
            ],
            [
                'nombreUsuario' => 'cliente2',
                'password' => Hash::make('password4'),
                'email' => 'cliente2@example.com',
                'telefono' => '5566778899',
                'rol' => 'Cliente',
                'idAutor' => 1,
                'eliminado' => 1
            ],
        ]);

        // Seed para la tabla 'entrenadores'
        DB::table('entrenadores')->insert([
            [
                'idUsuario' => 2,
                'nombre' => 'Juan',
                'primerApellido' => 'Pérez',
                'segundoApellido' => 'Gómez',
                'fechaNacimiento' => '1985-06-15',
                'direccion' => 'Calle Falsa 123',
                'especialidad' => 'Entrenamiento Personal',
                'genero' => 'Masculino',
                'descripcion' => 'Experto en entrenamiento personal',
                'fechaContratacion' => '2022-01-10',
                'idAutor' => 1,
                'eliminado' => 1
            ],
        ]);

        // Seed para la tabla 'clientes'
        DB::table('clientes')->insert([
            [
                'idUsuario' => 3,
                'nombre' => 'Ana',
                'primerApellido' => 'García',
                'segundoApellido' => 'López',
                'fechaNacimiento' => '1990-08-25',
                'genero' => 'Femenino',
                'direccion' => 'Avenida Siempreviva 742',
                'idAutor' => 1,
                'eliminado' => 1
            ],
            [
                'idUsuario' => 4,
                'nombre' => 'Pedro',
                'primerApellido' => 'Martínez',
                'segundoApellido' => 'Díaz',
                'fechaNacimiento' => '1992-11-30',
                'genero' => 'Masculino',
                'direccion' => 'Boulevard Las Palmas 303',
                'idAutor' => 1,
                'eliminado' => 1
            ],
        ]);

        // Seed para la tabla 'planesMembresia'
        DB::table('planesMembresia')->insert([
            [
                'nombrePlan' => 'Básico',
                'descripcion' => 'Acceso a todas las instalaciones durante 1 mes',
                'duracion' => 30,
                'precio' => 29.99,
                'idAutor' => 1,
                'eliminado' => 1
            ],
            [
                'nombrePlan' => 'Premium',
                'descripcion' => 'Acceso a todas las instalaciones y clases grupales durante 3 meses',
                'duracion' => 90,
                'precio' => 79.99,
                'idAutor' => 1,
                'eliminado' => 1
            ],
        ]);
        // Crear Usuarios Clientes

    }
}
