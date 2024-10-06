<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Empleado;
use App\Models\Usuario;
use App\Models\Entrenador;
use App\Models\Cliente;
use App\Models\Membresia;
use App\Models\Inscripcion;
use App\Models\Servicio;
use App\Models\Horario;
use App\Models\Asistencia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
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
         // Tabla Usuarios
         // 1. Crear el Administrador
         // Crear Usuario Admin
        DB::table('usuarios')->insert([
            'nombreUsuario' => 'admin',
            'password' => Hash::make('adminpassword'),
            'email' => 'admin@example.com',
            'rol' => 'Administrador',
            'eliminado' => 1,
            'idAutor' => 1,
            'fechaCreacion' => Carbon::now(),
        ]);

        // Crear Usuarios y Entrenadores
        for ($i = 1; $i <= 5; $i++) {
            DB::table('usuarios')->insert([
                'nombreUsuario' => 'entrenador' . $i,
                'password' => Hash::make('password' . $i),
                'email' => 'entrenador' . $i . '@example.com',
                'rol' => 'Entrenador',
                'eliminado' => 1,
                'idAutor' => 1,
                'fechaCreacion' => Carbon::now(),
            ]);

            DB::table('entrenadores')->insert([
                'idUsuario' => $i + 1,  // Relacionado con el usuario
                'nombre' => 'EntrenadorNombre' . $i,
                'primerApellido' => 'Apellido' . $i,
                'segundoApellido' => 'Apellido2' . $i,
                'fechaNacimiento' => Carbon::parse('1990-01-01'),
                'especialidad' => 'Entrenamiento Personal',
                'genero' => 'Masculino',
                'fechaContratacion' => Carbon::now(),
                'eliminado' => 1,
                'idAutor' => 1,
                'fechaCreacion' => Carbon::now(),
            ]);
        }

        // Crear Usuarios y Clientes
        for ($i = 1; $i <= 10; $i++) {
            DB::table('usuarios')->insert([
                'nombreUsuario' => 'cliente' . $i,
                'password' => Hash::make('password' . $i),
                'email' => 'cliente' . $i . '@example.com',
                'rol' => 'Cliente',
                'eliminado' => 1,
                'idAutor' => 1,
                'fechaCreacion' => Carbon::now(),
            ]);

            DB::table('clientes')->insert([
                'idUsuario' => $i + 6,  // Relacionado con el usuario
                'nombre' => 'ClienteNombre' . $i,
                'primerApellido' => 'Apellido' . $i,
                'segundoApellido' => 'Apellido2' . $i,
                'fechaNacimiento' => Carbon::parse('2000-01-01'),
                'genero' => 'Masculino',
                'telefonoEmergencia' => '123456789',
                'eliminado' => 1,
                'idAutor' => 1,
                'fechaCreacion' => Carbon::now(),
            ]);
        }

        // Crear Asistencias
        for ($i = 1; $i <= 10; $i++) {
            DB::table('asistencia')->insert([
                'idCliente' => $i,
                'fecha' => Carbon::now(),
                'horaEntrada' => Carbon::now()->format('H:i:s'),
                'horaSalida' => Carbon::now()->addHours(1)->format('H:i:s'),
                'eliminado' => 1,
                'idAutor' => 1,
            ]);
        }

        // Crear Membresías
        for ($i = 1; $i <= 6; $i++) {
            DB::table('membresias')->insert([
                'nombre' => 'Membresía ' . $i,
                'precio' => rand(100, 500),
                'duracion' => rand(30, 365),
                'diasAcceso' => 'todos',
                'descripcion' => 'Descripción de la membresía ' . $i,
                'eliminado' => 1,
                'idAutor' => 1,
                'fechaCreacion' => Carbon::now(),
            ]);
        }

        // Crear Inscripciones
        for ($i = 1; $i <= 6; $i++) {
            DB::table('inscripciones')->insert([
                'idCliente' => $i,
                'idMembresia' => $i,
                'fechaInicio' => Carbon::now(),
                'fechaFin' => Carbon::now()->addDays(30),
                'estado' => 'activa',
                'montoPago' => rand(100, 500),
                'estadoPago' => 'completado',
                'eliminado' => 1,
                'idAutor' => 1,
                'fechaCreacion' => Carbon::now(),
            ]);
        }

        // Crear Servicios
        for ($i = 1; $i <= 5; $i++) {
            DB::table('servicios')->insert([
                'nombre' => 'Servicio ' . $i,
                'descripcion' => 'Descripción del servicio ' . $i,
                'duracion' => 60,
                'precio' => rand(50, 200),
                'fechaInicio' => Carbon::now(),
                'fechaFin' => Carbon::now()->addMonths(6),
                'eliminado' => 1,
                'idAutor' => 1,
                'fechaCreacion' => Carbon::now(),
            ]);
        }

        // Crear Horarios
        for ($i = 1; $i <= 10; $i++) {
            DB::table('horarios')->insert([
                'idServicio' => ($i % 5) + 1,
                'idEntrenador' => ($i % 5) + 1,
                'diaSemana' => 'Lunes',
                'horaInicio' => '09:00:00',
                'horaFin' => '10:00:00',
                'capacidad' => rand(5, 20),
                'eliminado' => 1,
                'idAutor' => 1,
                'fechaCreacion' => Carbon::now(),
            ]);
        }

    }
}
