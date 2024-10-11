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
        ]);

        Cliente::create([
            'nombre' => 'Carlos',
            'primerApellido' => 'Mendoza',
            'segundoApellido' => 'Rojas',
            'fechaNacimiento' => '1980-10-30',
            'genero' => 'Masculino',
            'direccion' => 'Calle 12, Zona Norte',
            'telefonoEmergencia' => '789654321',
            'fechaCreacion' => now(),
            'eliminado' => 1,
            'idAutor' => 1,
        ]);
        
        Cliente::create([
            'nombre' => 'Lucía',
            'primerApellido' => 'Fernández',
            'segundoApellido' => 'Ruiz',
            'fechaNacimiento' => '1995-03-05',
            'genero' => 'Femenino',
            'direccion' => 'Av. Las Flores 456',
            'telefonoEmergencia' => '321789654',
            'fechaCreacion' => now(),
            'eliminado' => 1,
            'idAutor' => 1,
        ]);
        
        Cliente::create([
            'nombre' => 'Andrés',
            'primerApellido' => 'Guzmán',
            'segundoApellido' => 'Ortiz',
            'fechaNacimiento' => '1983-12-25',
            'genero' => 'Masculino',
            'direccion' => 'Av. Los Álamos 789',
            'telefonoEmergencia' => '654987321',
            'fechaCreacion' => now(),
            'eliminado' => 1,
            'idAutor' => 1,
        ]);
        
        Cliente::create([
            'nombre' => 'Sofía',
            'primerApellido' => 'Ramírez',
            'segundoApellido' => 'Flores',
            'fechaNacimiento' => '1998-08-10',
            'genero' => 'Femenino',
            'direccion' => 'Calle 3, Zona Sur',
            'telefonoEmergencia' => '147258369',
            'fechaCreacion' => now(),
            'eliminado' => 1,
            'idAutor' => 1,
        ]);
        DB::table('entrenadores')->insert([
            [
                'nombre' => 'Carlos',
                'primerApellido' => 'López',
                'segundoApellido' => 'Martínez',
                'telefono' => '5551234567',
                'descripcion' => 'Especialista en entrenamiento cardiovascular.',
                'especialidad' => 'Entrenamiento Cardiovascular',
                'genero' => 'Masculino',
               
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
                
                'fechaNacimiento' => '1985-08-22',
                'fechaContratacion' => '2023-02-05',
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            [
                'nombre' => 'Luis',
                'primerApellido' => 'Ramírez',
                'segundoApellido' => 'Fernández',
                'telefono' => '5556789012',
                'descripcion' => 'Especialista en fuerza y acondicionamiento físico.',
                'especialidad' => 'Boxeo',
                'genero' => 'Masculino',
                
                'fechaNacimiento' => '1988-03-22',
                'fechaContratacion' => '2023-03-15',
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            [
                'nombre' => 'Ana',
                'primerApellido' => 'Martínez',
                'segundoApellido' => 'Pérez',
                'telefono' => '5552345678',
                'descripcion' => 'Instructora de yoga con certificación internacional.',
                'especialidad' => 'Entrenamiento de Resistencia',
                'genero' => 'Femenino',
                
                'fechaNacimiento' => '1992-11-30',
                'fechaContratacion' => '2023-04-10',
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            [
                'nombre' => 'Pedro',
                'primerApellido' => 'Sánchez',
                'segundoApellido' => 'García',
                'telefono' => '5558765432',
                'descripcion' => 'Instructor de artes marciales y defensa personal.',
                'especialidad' => 'otro',
                'genero' => 'Masculino',
               
                'fechaNacimiento' => '1987-06-18',
                'fechaContratacion' => '2023-05-12',
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            [
                'nombre' => 'Elena',
                'primerApellido' => 'Vargas',
                'segundoApellido' => 'Díaz',
                'telefono' => '5553456789',
                'descripcion' => 'Entrenadora personal y especialista en rehabilitación física.',
                'especialidad' => 'Entrenamiento Cardiovascular',
                'genero' => 'Femenino',
                
                'fechaNacimiento' => '1990-02-14',
                'fechaContratacion' => '2023-06-01',
                'idAutor' => 1,
                'eliminado' => 1,
            ],
        ]);

        DB::table('servicios')->insert([
            [
                'nombre' => 'Clases de Spinning',
                'descripcion' => 'Clases de ciclismo indoor para mejorar la resistencia cardiovascular.',
                'duracion' => 120,
                'capacidadMaxima' => 20,
                'precioPorSeccion' => 50.00,
                'incluyeCostoEntrada' => 0,
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            [
                'nombre' => 'Boxeo para Principiantes',
                'descripcion' => 'Clases de boxeo enfocadas en técnica y acondicionamiento físico.',
                'duracion' => 120,
                'capacidadMaxima' => 15,
                'precioPorSeccion' => 70.00,
                'incluyeCostoEntrada' => 0,
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            [
                'nombre' => 'Yoga para la Relajación',
                'descripcion' => 'Clases de yoga diseñadas para reducir el estrés y mejorar la flexibilidad.',
                'duracion' => 120,
                'capacidadMaxima' => 25,
                'precioPorSeccion' => 40.00,
                'incluyeCostoEntrada' => 0,
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            [
                'nombre' => 'Entrenamiento Funcional',
                'descripcion' => 'Clases de alta intensidad para mejorar la fuerza y la resistencia.',
                'duracion' => 120,
                'capacidadMaxima' => 18,
                'precioPorSeccion' => 60.00,
                'incluyeCostoEntrada' => 1,
                'idAutor' => 1,
                'eliminado' => 1,
            ],
        ]);


        DB::table('membresias')->insert([
            [
                'nombre' => 'Membresía Mensual Básica',
                'descripcion' => 'Acceso a todas las áreas del gimnasio durante 30 días.',
                'duracionDias' => 30,
                'precio' => 250.00,
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            [
                'nombre' => 'Membresía Trimestral Premium',
                'descripcion' => 'Acceso a todas las áreas del gimnasio durante 90 días. Incluye 3 sesiones de entrenamiento personalizado.',
                'duracionDias' => 90,
                'precio' => 700.00,
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            [
                'nombre' => 'Membresía Anual Platino',
                'descripcion' => 'Acceso ilimitado al gimnasio por 1 año. Incluye evaluaciones mensuales de progreso.',
                'duracionDias' => 365,
                'precio' => 2200.00,
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            [
                'nombre' => 'Membresía Estudiante',
                'descripcion' => 'Acceso al gimnasio durante 30 días con descuento para estudiantes con carnet vigente.',
                'duracionDias' => 30,
                'precio' => 180.00,
                'idAutor' => 1,
                'eliminado' => 1,
            ],
        ]);

        
        DB::table('secciones')->insert([
            [
                'idServicio' => 1, // Clases de Spinning
                'fechaInicio' => '2024-11-01',
                'fechaFin' => '2024-12-01',
                'horaInicio' => '07:00:00',
                'horaFin' => '08:00:00',
                'capacidad' => 20,
                'idEntrenador' => 1,
                'estado' => 'activa',
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            [
                'idServicio' => 2, // Boxeo para Principiantes
                'fechaInicio' => '2024-10-20',
                'fechaFin' => '2024-12-20',
                'horaInicio' => '18:00:00',
                'horaFin' => '19:30:00',
                'capacidad' => 15,
                'idEntrenador' => 3,
                'estado' => 'activa',
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            [
                'idServicio' => 3, // Yoga para la Relajación
                'fechaInicio' => '2024-11-01',
                'fechaFin' => '2024-12-01',
                'horaInicio' => '09:00:00',
                'horaFin' => '10:30:00',
                'capacidad' => 25,
                'idEntrenador' => 2,
                'estado' => 'activa',
                'idAutor' => 1,
                'eliminado' => 1,
            ],
            [
                'idServicio' => 4, // Entrenamiento Funcional
                'fechaInicio' => '2024-10-25',
                'fechaFin' => '2024-12-25',
                'horaInicio' => '19:00:00',
                'horaFin' => '20:00:00',
                'capacidad' => 18,
                'idEntrenador' => 4,
                'estado' => 'activa',
                'idAutor' => 1,
                'eliminado' => 1,
            ],
        ]);
        
        DB::table('seccion_dias')->insert([
            ['idSeccion' => 1, 'idDia' => 1],  // Lunes
            ['idSeccion' => 1, 'idDia' => 3],  // Miércoles
            ['idSeccion' => 1, 'idDia' => 5],  // Viernes
        
            ['idSeccion' => 2, 'idDia' => 2],  // Martes
            ['idSeccion' => 2, 'idDia' => 4],  // Jueves
        
            ['idSeccion' => 3, 'idDia' => 3],  // Miércoles
            ['idSeccion' => 3, 'idDia' => 6],  // Sábado
        
            ['idSeccion' => 4, 'idDia' => 1],  // Lunes
            ['idSeccion' => 4, 'idDia' => 3],  // Miércoles
            ['idSeccion' => 4, 'idDia' => 5],  // Viernes
        ]);
        



    }
}
