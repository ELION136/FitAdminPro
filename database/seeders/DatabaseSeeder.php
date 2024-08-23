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
      

        // Crear Usuarios Clientes
        for ($i = 1; $i <= 4; $i++) {
            $userId = DB::table('usuarios')->insertGetId([
                'nombreUsuario' => 'client'.$i,
                'password' => Hash::make('clientpassword'.$i), // Cambia esta contraseña
                'email' => 'client'.$i.'@gmail.com',
                'rol' => 'Cliente',
                'idAutor' => 1, // El administrador es el autor
                'fechaCreacion' => now(),
                'eliminado' => 1,
            ]);

            DB::table('clientes')->insert([
                'idUsuario' => $userId,
                'nombre' => 'Client'.$i,
                'primerApellido' => 'Apellido'.$i,
                'fechaNacimiento' => '1990-01-01',
                'genero' => 'Masculino',
                'idAutor' => 1, // El administrador es el autor
                'fechaCreacion' => now(),
                'eliminado' => 1,
            ]);
        }
    }
}
