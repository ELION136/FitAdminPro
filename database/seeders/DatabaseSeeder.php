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
       
         // Sembrar la tabla de Usuarios
         $planes = [
            [
                'nombrePlan' => 'Básico',
                'descripcion' => 'Acceso a todas las áreas comunes y clases grupales.',
                'duracion' => 1,
                'precio' => 30.00,
                'idAutor' => 1, // Asumiendo que el usuario con id 1 es el autor
                'eliminado' => 1
            ],
            [
                'nombrePlan' => 'Intermedio',
                'descripcion' => 'Acceso a todas las áreas, clases grupales y una sesión con un entrenador personal.',
                'duracion' => 3,
                'precio' => 80.00,
                'idAutor' => 1,
                'eliminado' => 1
            ],
            [
                'nombrePlan' => 'Avanzado',
                'descripcion' => 'Acceso completo, clases grupales, y dos sesiones con un entrenador personal por mes.',
                'duracion' => 6,
                'precio' => 150.00,
                'idAutor' => 1,
                'eliminado' => 1
            ],
            [
                'nombrePlan' => 'Premium',
                'descripcion' => 'Acceso total, clases grupales, sesiones ilimitadas con un entrenador personal, y acceso a áreas VIP.',
                'duracion' => 12,
                'precio' => 250.00,
                'idAutor' => 1,
                'eliminado' => 1
            ],
        ];

        foreach ($planes as $plan) {
            PlanMembresia::create($plan);
        }
    }
}
