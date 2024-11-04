<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use Carbon\Carbon;
use App\Models\DiaSemana;
use App\Models\Entrenador;
use App\Models\CategoriaServicio;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class ServiciosController extends Controller
{
    public function index()
    {
        $servicios = Servicio::where('eliminado', 1)->with('diasSemana')->get();
        $entrenadores = Entrenador::where('eliminado', 1)->get();
        $categorias = CategoriaServicio::all();

        return view('admin.servicios.index', compact('servicios', 'entrenadores', 'categorias'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'capacidad' => 'required|integer|min:1',
            'precioTotal' => 'required|numeric|min:0|max:10000',
            'cantidadSesiones' => 'nullable|integer|min:1',
            'idEntrenador' => 'required|exists:entrenadores,idEntrenador',
            'idCategoria' => 'required|exists:categorias_servicios,idCategoria',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $servicio = Servicio::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'capacidad' => $request->capacidad,
            'precioTotal' => $request->precioTotal,
            'cantidadSesiones' => $request->cantidadSesiones,
            'idCategoria' => $request->idCategoria,
            'idEntrenador' => $request->idEntrenador,
            'estado' => 0, // Inicialmente inactivo
            'idAutor' => Auth::id(),
            'eliminado' => 1
        ]);

        return response()->json(['success' => 'Servicio creado exitosamente, asigna horarios para activarlo.']);
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'capacidad' => 'required|integer|min:1',
            'precioTotal' => 'required|numeric|min:0|max:10000',
            'cantidadSesiones' => 'nullable|integer|min:1',
            'idEntrenador' => 'required|exists:entrenadores,idEntrenador',
            'idCategoria' => 'required|exists:categorias_servicios,idCategoria',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $servicio->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'capacidad' => $request->capacidad,
            'precioTotal' => $request->precioTotal,
            'cantidadSesiones' => $request->cantidadSesiones,
            'idCategoria' => $request->idCategoria,
            'idEntrenador' => $request->idEntrenador,
            'idAutor' => Auth::id(),
        ]);

        return response()->json(['success' => 'Servicio actualizado exitosamente.']);
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->update(['eliminado' => 0]);

        return response()->json(['success' => 'Servicio eliminado exitosamente.']);
    }



    public function calendarioGeneral()
    {
        // Obtener todos los servicios con sus días y horarios
        $servicios = Servicio::with('diasSemana')->get();
        
        // Preparar los eventos para el calendario
        $horarios = [];
        
        foreach ($servicios as $servicio) {
            foreach ($servicio->diasSemana as $diaSemana) {
                // Convertir día a número (0-6)
                $diaFC = match(strtolower($diaSemana->nombreDia)) {
                    'domingo' => 0,
                    'lunes' => 1,
                    'martes' => 2,
                    'miércoles' => 3,
                    'jueves' => 4,
                    'viernes' => 5,
                    'sábado' => 6,
                    default => null
                };
                
                if ($diaFC !== null) {
                    $horarios[] = [
                        'title' => $servicio->nombre,
                        'startTime' => substr($diaSemana->pivot->horaInicio, 0, 5),  // Formato HH:mm
                        'endTime' => substr($diaSemana->pivot->horaFin, 0, 5),      // Formato HH:mm
                        'daysOfWeek' => [$diaFC],
                        'color' => '#' . substr(md5($servicio->nombre), 0, 6),
                        'startRecur' => now()->startOfWeek()->format('Y-m-d'),
                        'endRecur' => now()->addYears(1)->format('Y-m-d'),
                    ];
                }
            }
        }
        
        return view('admin.servicios.horarios.calendarioGeneral', compact('horarios'));
    }


}
