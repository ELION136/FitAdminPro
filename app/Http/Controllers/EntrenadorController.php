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
        // Obtener el usuario autenticado
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        // Formatear y validar la solicitud
        $request->merge([
            'nombre' => Str::title(trim($request->input('nombre'))),
            'primerApellido' => Str::title(trim($request->input('primerApellido'))),
            'segundoApellido' => Str::title(trim($request->input('segundoApellido')))
        ]);

        $request->validate([
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
        ]);

        // Subir y guardar la imagen si existe
        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put($imageName, file_get_contents($image));
        }

        // Crear el entrenador
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

        // Verificar que el entrenador se ha creado correctamente
        if (!$entrenador) {
            return redirect()->back()->with('error', 'Error al crear el entrenador');
        }

        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.entrenadores.index')
            ->with('mensaje', 'El entrenador fue registrado correctamente.')
            ->with('icono', 'success');
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

        $request->validate([
            'nombre' => 'required|string|max:50',
            'primerApellido' => 'required|string|max:50',
            'segundoApellido' => 'nullable|string|max:50',
            'telefono' => 'nullable|digits_between:7,15',
            'fechaNacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'fechaContratacion' => 'required|date',
            'direccion' => 'nullable|string|max:50',
            'especialidad' => 'required',
            'descripcion' => 'nullable|string|max:100',
        ]);

        // Actualizar la imagen si se ha subido una nueva
        if ($request->hasFile('image')) {
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

        return redirect()->route('admin.entrenadores.index')
            ->with('mensaje', 'El entrenador se actualizó correctamente.')
            ->with('icono', 'success');
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
    public function exportExcel()
    {
        return Excel::download(new EntrenadoresExport, 'entrenadores.xlsx');
    }

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
