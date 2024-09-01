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
        DB::table('planesMembresia')->insert([
            [
                'nombrePlan' => 'Plan Básico',
                'descripcion' => 'Acceso al gimnasio de lunes a viernes en horario reducido.',
                'duracion' => 30, // 30 días
                'precio' => 19.99,
                'idAutor' => 1, // Usuario que realiza la acción
                'eliminado' => 1, // Activo
            ],
            [
                'nombrePlan' => 'Plan Estándar',
                'descripcion' => 'Acceso completo al gimnasio todos los días.',
                'duracion' => 30, // 30 días
                'precio' => 29.99,
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            [
                'nombrePlan' => 'Plan Premium',
                'descripcion' => 'Acceso completo al gimnasio, clases especiales, y entrenador personal.',
                'duracion' => 30, // 30 días
                'precio' => 49.99,
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            [
                'nombrePlan' => 'Plan Anual',
                'descripcion' => 'Acceso completo al gimnasio durante todo el año.',
                'duracion' => 365, // 365 días
                'precio' => 299.99,
                'idAutor' => 1,
                'eliminado' => 1,
            ],
        ]);


        // Crear Usuarios Clientes

    }
}
