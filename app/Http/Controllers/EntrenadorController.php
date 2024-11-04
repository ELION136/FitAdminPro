<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrenador;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\EntrenadoresExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class EntrenadorController extends Controller
{
    public function index()
    {
        $entrenadores = Entrenador::where('eliminado', 1)->get();
        return view('admin.entrenadores.index', compact('entrenadores'));
    }

    /**
     * Muestra el formulario para crear un nuevo entrenador.
     */
    public function create()
    {
        return view('admin.entrenadores.create');
    }

    /**
     * Almacena un nuevo entrenador en la base de datos.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $request->merge([
            'nombre' => Str::title(trim($request->input('nombre'))),
            'primerApellido' => Str::title(trim($request->input('primerApellido'))),
            'segundoApellido' => Str::title(trim($request->input('segundoApellido')))
        ]);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50|regex:/^[^\s].*[^\s]$/',
            'primerApellido' => 'required|string|max:50|regex:/^[^\s].*[^\s]$/',
            'segundoApellido' => 'nullable|string|max:50|regex:/^[^\s].*[^\s]$/',
            'telefono' => 'nullable|digits_between:7,15',
            'fechaNacimiento' => 'required|date|before:today',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'fechaContratacion' => 'required|date|before_or_equal:today',
            'direccion' => 'nullable|string|max:50',
            'especialidad' => 'required',
            'descripcion' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put($imageName, file_get_contents($image));
        }

        $entrenador = Entrenador::create([
            'nombre' => $request->nombre,
            'primerApellido' => $request->primerApellido,
            'segundoApellido' => $request->segundoApellido,
            'telefono' => $request->telefono,
            'fechaNacimiento' => $request->fechaNacimiento,
            'genero' => $request->genero,
            'fechaContratacion' => $request->fechaContratacion,
            'direccion' => $request->direccion,
            'especialidad' => $request->especialidad,
            'descripcion' => $request->descripcion,
            'image' => $imageName,
            'idAutor' => $user->idUsuario,
            'fechaCreacion' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'El entrenador fue registrado correctamente.']);
    }
    /**
     * Muestra el formulario para editar un entrenador.
     */
    public function edit($id)
    {
        $entrenador = Entrenador::findOrFail($id);
        return view('admin.entrenadores.edit', compact('entrenador'));
    }

    /**
     * Actualiza un entrenador en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $entrenador = Entrenador::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50|regex:/^[\pL\s]+$/u', // Solo letras y espacios, sin caracteres especiales
            'primerApellido' => 'required|string|max:50|regex:/^[\pL\s]+$/u', // Solo letras y espacios
            'segundoApellido' => 'nullable|string|max:50|regex:/^[\pL\s]+$/u', // Solo letras y espacios
            'telefono' => 'nullable|digits_between:7,15', // Solo dígitos con longitud entre 7 y 15
            'fechaNacimiento' => 'required|date|before:today|after:1900-01-01', // Fecha válida antes de hoy y después de 1900
            'genero' => 'required|in:Masculino,Femenino,Otro', // Solo los valores permitidos
            'fechaContratacion' => 'required|date|before_or_equal:today|after:fechaNacimiento', // Fecha válida antes o igual a hoy y después de nacimiento
            'direccion' => 'nullable|string|max:100', // Extendido el límite para detalles más largos
            'especialidad' => 'required|string|in:Entrenamiento Personal,Entrenamiento Cardiovascular,Boxeo,Entrenamiento de Resistencia,Nutrición y Bienestar,Otro', // Especialidades permitidas
            'descripcion' => 'nullable|string|max:100', // Máximo 100 caracteres para la descripción
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de imagen (jpeg, png, jpg, gif) y tamaño máximo de 2MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('image')) {
            if ($entrenador->image) {
                Storage::disk('public')->delete($entrenador->image);
            }
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put($imageName, file_get_contents($image));
            $entrenador->image = $imageName;
        }

        $entrenador->update([
            'nombre' => $request->nombre,
            'primerApellido' => $request->primerApellido,
            'segundoApellido' => $request->segundoApellido,
            'telefono' => $request->telefono,
            'fechaNacimiento' => $request->fechaNacimiento,
            'genero' => $request->genero,
            'fechaContratacion' => $request->fechaContratacion,
            'direccion' => $request->direccion,
            'especialidad' => $request->especialidad,
            'descripcion' => $request->descripcion,
            'idAutor' => $user->idUsuario,
        ]);

        return response()->json(['success' => true, 'message' => 'El entrenador se actualizó correctamente.']);
    }

    /**
     * Elimina lógicamente un entrenador.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        $entrenador = Entrenador::findOrFail($id);

        // Actualizar la tabla entrenadores
        $entrenador->update([
            'eliminado' => 0, // Marcando como eliminado
            'idAutor' => $user->idUsuario,
        ]);

        return redirect()->route('admin.entrenadores.index')
            ->with('mensaje', 'Entrenador eliminado con éxito.')
            ->with('icono', 'success');
    }

    /*public function show($id)
    {
        $entrenador = Entrenador::with('usuario')->findOrFail($id);
        return response()->json($entrenador);
    }*/
    public function forceDestroy($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        $entrenador = Entrenador::findOrFail($id);

        // Eliminar el usuario asociado
        $usuario = User::findOrFail($entrenador->idUsuario);
        $usuario->delete();

        // Eliminar el empleado
        $entrenador->delete();

        return redirect()->route('admin.entrenadores.index')->with('mensaje', 'Empleado eliminado permanentemente con éxito')->with('icono', 'success');
    }


    public function eliminados()
    {
        $eliminados = Entrenador::with('usuario')->where('eliminado', 0)->get();
        return view('admin.entrenadores.eliminados', compact('eliminados'));
    }



    public function restore($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        $entrenador = Entrenador::findOrFail($id);

        // Cambiar el estado de 'eliminado' de 0 a 1
        $entrenador->update([
            'eliminado' => 1,
            'idAutor' => $user->idUsuario,
        ]);

        // Actualizar la tabla usuarios asociada
        $usuario = User::findOrFail($entrenador->idUsuario);
        $usuario->update([
            'eliminado' => 1,
            'idAutor' => $user->idUsuario,
        ]);

        return redirect()->route('admin.entrenadores.index')->with('mensaje', 'Entrenador restaurado con éxito')->with('icono', 'success');
    }

    public function exportPDF()
    {
        $entrenadores = Entrenador::paginate(50);
        $entrenadores = Entrenador::with('usuario')->where('eliminado', 1)->get();
        $pdf = Pdf::loadView('admin.entrenadores.pdf', compact('entrenadores'));

        // Personaliza el PDF
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        //return $pdf->download('entrenadores.pdf');
        return $pdf->download('entrenadores.pdf');
    }
    //public function exportExcel()
    //{
    //   return Excel::download(new EntrenadoresExport, 'entrenadores.xlsx');
    // }

    // UserController.php

    // EntrenadorController.php
    public function indexCliente(Request $request)
    {
        $nombre = $request->get('nombre');
        $especialidad = $request->get('especialidad');

        $entrenadores = Entrenador::when($nombre, function ($query, $nombre) {
            return $query->where(function ($query) use ($nombre) {
                $query->where('nombre', 'like', "%$nombre%")
                    ->orWhere('primerApellido', 'like', "%$nombre%")
                    ->orWhere('segundoApellido', 'like', "%$nombre%");
            });
        })
            ->when($especialidad, function ($query, $especialidad) {
                return $query->where('especialidad', $especialidad);
            })
            ->get();

        return view('cliente.entrenadores.index', compact('entrenadores'));
    }



}
