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
      /*  DB::table('usuarios')->insert([
            'nombreUsuario' => 'admin',
            'password' => Hash::make('adminpassword'),
            'email' => 'admin@gmail.com',
            'rol' => 'Administrador',
            'eliminado' => 1,
            'idAutor' => 1,
            'fechaCreacion' => Carbon::now(),
        ]);


        Cliente::create([
            'nombre' => 'Juan',
            'primerApellido' => 'Pérez',
            'segundoApellido' => 'Gómez',
            'fechaNacimiento' => '1990-05-15',
            'genero' => 'Masculino',
            'direccion' => 'Av. Siempre Viva 123',
            'telefonoEmergencia' => '789456123',
            'fechaCreacion' => now(),
            'eliminado' => 1,
            'idAutor'=>1,
        ]);

        Cliente::create([
            'nombre' => 'María',
            'primerApellido' => 'Rodríguez',
            'segundoApellido' => 'López',
            'fechaNacimiento' => '1985-07-22',
            'genero' => 'Femenino',
            'direccion' => 'Calle 8, Zona Central',
            'telefonoEmergencia' => '123456789',
            'fechaCreacion' => now(),
            'eliminado' => 1,
            'idAutor'=>1,
        ]);*/
        DB::table('entrenadores')->insert([
            [
                'nombre' => 'Carlos',
                'primerApellido' => 'López',
                'segundoApellido' => 'Martínez',
                'telefono' => '5551234567',
                'descripcion' => 'Especialista en entrenamiento cardiovascular.',
                'especialidad' => 'Entrenamiento Cardiovascular',
                'genero' => 'Masculino',
                'image' => 'carlos_lopez.jpg',
                'fechaNacimiento' => '1990-04-15',
                'fechaContratacion' => '2023-01-10',
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            [
                'nombre' => 'María',
                'primerApellido' => 'González',
                'segundoApellido' => 'Hernández',
                'telefono' => '5559876543',
                'descripcion' => 'Experta en nutrición y bienestar.',
                'especialidad' => 'Nutrición y Bienestar',
                'genero' => 'Femenino',
                'image' => 'maria_gonzalez.jpg',
                'fechaNacimiento' => '1985-08-22',
                'fechaContratacion' => '2023-02-05',
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            // Agrega más registros según sea necesario
        ]);



    }
}
