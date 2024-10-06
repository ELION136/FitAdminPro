<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Entrenador;
use App\Models\Asistencia;
use App\Models\Servicio;
use App\Models\Inscripcion;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientesExport;
use Illuminate\Support\Facades\Mail;
class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::with('usuario')->where('eliminado', 1)->get();
        return view('admin.clientes.index', compact('clientes'));
    }


    //ShouldQueue  este metodo hace colas
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */


    private function generatePassword()
    {
        $prefix = 'fitadminpro';
        $randomNumbers = rand(1000, 9999);
        return $prefix . $randomNumbers;
    }



    public function store(Request $request)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        // Validar la solicitud
        $request->validate([
            'nombreUsuario' => 'required|unique:usuarios,nombreUsuario',
            'email' => 'required|email|unique:usuarios,email',
            'telefono' => 'nullable',
            'nombre' => 'required',
            'primerApellido' => 'required',
            'segundoApellido' => 'nullable',
            'fechaNacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino,Otro',
        ]);


        $temporaryPassword = $this->generatePassword();
        // Crear el usuario
        $usuario = User::create([
            'nombreUsuario' => $request->nombreUsuario,
            'email' => $request->email,
            'password' => hash::make($temporaryPassword),
            'telefono' => $request->telefono,
            'rol' => 'Cliente',
            'idAutor' => $user->idUsuario, // Utilizar 'idUsuario' del modelo autenticado
            'fechaCreacion' => now(),
        ]);

        // Verificar que el usuario se ha creado correctamente
        if (!$usuario) {
            return redirect()->back()->with('error', 'Error al crear el usuario');
        }

        // Verificar que el ID del usuario no es null
        if (is_null($usuario->idUsuario)) {
            return redirect()->back()->with('error', 'El ID del usuario es null');
        }



        // Crear el cliente asociado
        $cliente = Cliente::create([
            'idUsuario' => $usuario->idUsuario, // Utilizar 'idUsuario' del usuario creado
            'nombre' => $request->nombre,
            'primerApellido' => $request->primerApellido,
            'segundoApellido' => $request->segundoApellido,
            'fechaNacimiento' => $request->fechaNacimiento,
            'genero' => $request->genero,
            'idAutor' => $user->idUsuario, // Utilizar 'idUsuario' del modelo autenticado
            'fechaCreacion' => now(),
        ]);

        // Verificar que el empleado se ha creado correctamente
        if (!$cliente) {
            return redirect()->back()->with('error', 'Error al crear el empleado');
        }


        Mail::to($usuario->email)->send(new \App\Mail\TemporaryPasswordMail($usuario, $temporaryPassword));

        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.clientes.index')->with('mensaje', 'El registro fue realizado correctamente y se envió un correo electrónico al empleado.')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        $usuario = User::findOrFail($cliente->idUsuario);
        return view('admin.clientes.edit', compact('cliente', 'usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $cliente = Cliente::findOrFail($id);
        $request->validate([
            'nombreUsuario' => 'required|unique:usuarios,nombreUsuario,' . $cliente->idUsuario . ',idUsuario',
            'email' => 'required|email|unique:usuarios,email,' . $cliente->idUsuario . ',idUsuario',
            'telefono' => 'nullable',
            'nombre' => 'required',
            'primerApellido' => 'required',
            'segundoApellido' => 'nullable',
            'fechaNacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino,Otro',
        ]);

        $usuario = User::findOrFail($cliente->idUsuario);
        $usuario->update([
            'nombreUsuario' => $request->nombreUsuario,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'idAutor' => $user->idUsuario
        ]);

        $cliente->update([
            'nombre' => $request->nombre,
            'primerApellido' => $request->primerApellido,
            'segundoApellido' => $request->segundoApellido,
            'fechaNacimiento' => $request->fechaNacimiento,
            'genero' => $request->genero,
            'idAutor' => $user->idUsuario
        ]);

        return redirect()->route('admin.clientes.index')->with('mensaje', 'Se actualizó el registro correctamente.')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        $cliente = cliente::findOrFail($id);

        // Actualizar la tabla empleados
        $cliente->update([
            'eliminado' => 0, // Marcando como eliminado
            'idAutor' => $user->idUsuario,
        ]);

        // Actualizar la tabla usuarios
        $usuario = User::findOrFail($cliente->idUsuario);
        $usuario->update([
            'eliminado' => 0, // Marcando como eliminado
            'idAutor' => $user->idUsuario,
        ]);

        return redirect()->route('admin.clientes.index')->with('mensaje', 'Empleado eliminado con éxito')->with('icono', 'success');
    }



    //para el perfil del cliente 

    public function profile()
    {
        $user = Auth::user();
        $cliente = $user->cliente;
        return view('admin.clientes.profile', compact('user', 'cliente'));
    }

    // Actualiza el perfil del usuario autenticado
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $cliente = $user->cliente;

        // Validar los datos del request de forma básica
        $request->validate([
            'nombreUsuario' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email,' . $user->idUsuario . ',idUsuario',
            'telefono' => 'nullable|string|max:15',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nombre' => 'required|string|max:50',
            'primerApellido' => 'required|string|max:50',
            'segundoApellido' => 'nullable|string|max:50',
            'fechaNacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            $user->nombreUsuario = $request->nombreUsuario;
            $user->email = $request->email;
            $user->telefono = $request->telefono;
            $user->idAutor = Auth::id();

            if ($request->hasFile('image')) {
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }
                $path = $request->file('image')->store('profile_images', 'public');
                $user->image = $path;
            }

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            $cliente->nombre = $request->nombre;
            $cliente->primerApellido = $request->primerApellido;
            $cliente->segundoApellido = $request->segundoApellido;
            $cliente->fechaNacimiento = $request->fechaNacimiento;
            $cliente->genero = $request->genero;
            $cliente->idAutor = Auth::id();

            $cliente->save();

            DB::commit();

            return redirect()->route('admin.clientes.profile')->with('success', 'Perfil actualizado con éxito.')->with('icono', 'success');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Hubo un error al actualizar el perfil. Por favor, inténtalo nuevamente.');
        }
    }



    public function forceDestroy($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        $cliente = Cliente::findOrFail($id);

        // Eliminar el usuario asociado
        $usuario = User::findOrFail($cliente->idUsuario);
        $usuario->delete();

        // Eliminar el empleado
        $cliente->delete();

        return redirect()->route('admin.clientes.index')->with('mensaje', 'Cliente eliminado permanentemente con éxito')->with('icono', 'success');
    }


    public function eliminados()
    {
        $eliminados = Cliente::with('usuario')->where('eliminado', 0)->get();
        return view('admin.clientes.eliminados', compact('eliminados'));
    }



    public function restore($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        $cliente = Cliente::findOrFail($id);

        // Cambiar el estado de 'eliminado' de 0 a 1
        $cliente->update([
            'eliminado' => 1,
            'idAutor' => $user->idUsuario,
        ]);

        // Actualizar la tabla usuarios asociada
        $usuario = User::findOrFail($cliente->idUsuario);
        $usuario->update([
            'eliminado' => 1,
            'idAutor' => $user->idUsuario,
        ]);

        return redirect()->route('admin.clientes.index')->with('mensaje', 'Cliente restaurado con éxito')->with('icono', 'success');
    }


    public function exportPDF()
    {

        $clientes1 = Cliente::paginate(50);
        $clientes = Cliente::with('usuario')->where('eliminado', 1)->get();
        $pdf = Pdf::loadView('admin.clientes.pdf', compact('clientes', 'clientes1'));

        // Personaliza el PDF
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        //return $pdf->download('entrenadores.pdf');
        //return $pdf->stream('clientes.pdf');
        return $pdf->download('clientes.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ClientesExport, 'clientes.xlsx');
    }


    //CLIENTE

    public function dashboard()
    {
        // Obtener cliente actual
        $cliente = Cliente::where('idUsuario', auth()->user()->idUsuario)->first();

        // Verifica si el cliente tiene una inscripción activa
        $inscripcionActiva = $cliente->inscripciones()
            ->where('estado', 'activa')
            ->where('fechaFin', '>=', now())
            ->first();

        // Obtener las asistencias del mes y del año usando los métodos del modelo
        $asistenciasMes = $cliente->asistenciasMes();
        $asistenciasAnio = $cliente->asistenciasAnio();

        // Calcular el porcentaje de asistencias del mes y del año
        $porcentajeMes = $this->calcularPorcentajeAsistencias($asistenciasMes);
        $porcentajeAnio = $this->calcularPorcentajeAsistencias($asistenciasAnio);

        // Obtener servicios y entrenadores
        $servicios = Servicio::all();
        $entrenadores = Entrenador::all();

        return view('cliente.dashboard', compact(
            'cliente',
            'asistenciasMes',
            'asistenciasAnio',
            'porcentajeMes',
            'porcentajeAnio',
            'servicios',
            'entrenadores',
            'inscripcionActiva' // Pasamos solo los datos necesarios
        ));
    }

    private function calcularPorcentajeAsistencias($asistencias)
    {
        // Calcula un porcentaje basado en asistencias, puedes ajustar según tu lógica
        $totalDias = 30; // por ejemplo, 30 días en un mes
        return ($asistencias / $totalDias) * 100;
    }

    public function obtenerAsistenciasPorMes($idCliente)
    {
        // Aquí puedes crear la lógica para obtener las asistencias por cada mes
        // Esto es solo un ejemplo y depende de cómo estás manejando las asistencias en tu base de datos.

        // Supongamos que tienes una tabla de asistencias donde guardas la fecha de cada asistencia,
        // podrías agrupar por mes y contar el número de asistencias en cada mes.

        $asistenciasPorMes = Asistencia::where('idCliente', $idCliente)
            ->selectRaw('MONTH(fecha) as mes, COUNT(*) as total')
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes')->toArray();

        // Crear un array para todos los meses con 0 por defecto
        $asistenciasMeses = array_fill(1, 12, 0);

        // Reemplazar los valores de los meses que tienen asistencias
        foreach ($asistenciasPorMes as $mes => $total) {
            $asistenciasMeses[$mes] = $total;
        }

        return array_values($asistenciasMeses); // Devolver los datos en un array simple de 12 elementos
    }

    public function asistencias(Request $request)
    {
        $clienteId = auth()->user()->idCliente;

        $start = $request->input('start');
        $end = $request->input('end');

        \Log::info('Cliente ID: ' . $clienteId); // Log para verificar el cliente

        $asistencias = Cliente::find($clienteId)->asistencia()
            ->whereBetween('fecha', [$start, $end])
            ->get();

        \Log::info('Asistencias: ', $asistencias->toArray()); // Log para verificar las asistencias

        $eventos = $asistencias->map(function ($asistencia) {
            return [
                'title' => 'Asistencia',
                'start' => $asistencia->fecha->toISOString(),
                'allDay' => true,
            ];
        });

        return response()->json($eventos);
    }





}
