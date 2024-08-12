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
        $adminId = DB::table('usuarios')->insertGetId([
            'nombreUsuario' => 'admin',
            'password' => Hash::make('adminpassword'), // Cambia esta contraseña
            'email' => 'admin@example.com',
            'rol' => 'Administrador',
            'idAutor' => 1, // Referenciando a sí mismo
            'fechaCreacion' => now(),
            'eliminado' => 1,
        ]);

        // Crear Usuarios Empleados
        $empleadoIds = [];
        for ($i = 1; $i <= 2; $i++) {
            $userId = DB::table('usuarios')->insertGetId([
                'nombreUsuario' => 'empleado'.$i,
                'password' => Hash::make('empleadopassword'.$i), // Cambia esta contraseña
                'email' => 'empleado'.$i.'@example.com',
                'rol' => 'Empleado',
                'idAutor' => 1, // El administrador es el autor
                'fechaCreacion' => now(),
                'eliminado' => 1,
            ]);

            $empleadoIds[] = DB::table('empleados')->insertGetId([
                'idUsuario' => $userId,
                'nombre' => 'Empleado'.$i,
                'primerApellido' => 'Apellido'.$i,
                'fechaNacimiento' => '1980-01-01',
                'especialidad' => 'Entrenamiento Personal',
                'genero' => 'Masculino',
                'fechaContratacion' => now(),
                'idAutor' => 1, // El administrador es el autor
                'fechaCreacion' => now(),
                'eliminado' => 1,
            ]);
        }

        // Crear Usuarios Clientes
        for ($i = 1; $i <= 2; $i++) {
            $userId = DB::table('usuarios')->insertGetId([
                'nombreUsuario' => 'cliente'.$i,
                'password' => Hash::make('clientepassword'.$i), // Cambia esta contraseña
                'email' => 'cliente'.$i.'@example.com',
                'rol' => 'Cliente',
                'idAutor' => 1, // El administrador es el autor
                'fechaCreacion' => now(),
                'eliminado' => 1,
            ]);

            DB::table('clientes')->insert([
                'idUsuario' => $userId,
                'nombre' => 'Cliente'.$i,
                'primerApellido' => 'Apellido'.$i,
                'fechaNacimiento' => '1990-01-01',
                'genero' => 'Femenino',
                'idAutor' => 1, // El administrador es el autor
                'fechaCreacion' => now(),
                'eliminado' => 1,
            ]);
        }
    }
}
