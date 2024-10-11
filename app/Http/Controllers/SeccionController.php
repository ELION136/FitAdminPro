<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Seccion;
use App\Models\Servicio;
use App\Models\DiaSemana;
use App\Models\Entrenador;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SeccionController extends Controller
{
    public function index()
    {
        $secciones = Seccion::with(['servicio', 'entrenador', 'dias'])->where('eliminado', 1)->get();
        $servicios = Servicio::where('eliminado', 1)->get();
        $entrenadores = Entrenador::where('eliminado', 1)->get();
        $dias = DiaSemana::all();

        return view('admin.secciones.index', compact('secciones', 'servicios', 'entrenadores', 'dias'));
    }
    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'idServicio' => 'required|exists:servicios,idServicio',
            'fechaInicio' => 'required|date|after_or_equal:today',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
            'horaInicio' => 'required|date_format:H:i|after_or_equal:08:00|before_or_equal:22:00',
            'idEntrenador' => 'nullable|exists:entrenadores,idEntrenador',
            'dias' => 'required|array|min:1',
            'dias.*' => 'exists:dias_semana,idDia',
        ], [
            'fechaFin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        ]);

        // Obtener el servicio y la duración en minutos
        $servicio = Servicio::findOrFail($request->idServicio);
        $duracion = $servicio->duracion; // Duración en minutos

        // Calcular la hora de finalización basada en la duración del servicio
        $horaInicio = new \DateTime($request->horaInicio);
        $horaFin = clone $horaInicio;
        $horaFin->modify("+{$duracion} minutes");

        // Verificar que la hora de finalización no exceda las 22:00
        $horaCierre = new \DateTime('22:00');
        if ($horaFin > $horaCierre) {
            return response()->json(['errors' => ['horaFin' => ['La hora de finalización excede el horario de cierre (22:00).']]], 422);
        }

        // Validar que el rango de fechas sea al menos una semana
        $fechaInicio = new \DateTime($request->fechaInicio);
        $fechaFin = new \DateTime($request->fechaFin);
        $interval = $fechaInicio->diff($fechaFin);

        if ($interval->days < 7) {
            return response()->json(['errors' => ['fechaFin' => ['El rango de fechas debe ser de al menos una semana.']]], 422);
        }

        // Crear la nueva sección
        $seccion = Seccion::create([
            'idServicio' => $request->idServicio,
            'fechaInicio' => $request->fechaInicio,
            'fechaFin' => $request->fechaFin,
            'horaInicio' => $horaInicio->format('H:i'),
            'horaFin' => $horaFin->format('H:i'),
            'capacidad' => $servicio->capacidadMaxima,
            'idEntrenador' => $request->idEntrenador,
            'idAutor' => auth()->id(),
        ]);

        // Guardar los días seleccionados
        $seccion->dias()->sync($request->dias);

        return response()->json(['success' => 'Sección creada exitosamente.']);
    }

    public function update(Request $request, $id)
    {
        // Validar la solicitud
        $request->validate([
            'idServicio' => 'required|exists:servicios,idServicio',
            'fechaInicio' => 'required|date|after_or_equal:today',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
            'horaInicio' => 'required|date_format:H:i|after_or_equal:08:00|before_or_equal:22:00',
            'idEntrenador' => 'nullable|exists:entrenadores,idEntrenador',
            'dias' => 'required|array|min:1',
            'dias.*' => 'exists:dias_semana,idDia',
        ], [
            'fechaFin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        ]);

        // Obtener el servicio y la duración en minutos
        $servicio = Servicio::findOrFail($request->idServicio);
        $duracion = $servicio->duracion;

        // Calcular la hora de finalización
        $horaInicio = new \DateTime($request->horaInicio);
        $horaFin = clone $horaInicio;
        $horaFin->modify("+{$duracion} minutes");

        // Verificar el horario de cierre
        $horaCierre = new \DateTime('22:00');
        if ($horaFin > $horaCierre) {
            return response()->json(['errors' => ['horaFin' => ['La hora de finalización excede el horario de cierre (22:00).']]], 422);
        }

        // Validar el rango de fechas
        $fechaInicio = new \DateTime($request->fechaInicio);
        $fechaFin = new \DateTime($request->fechaFin);
        $interval = $fechaInicio->diff($fechaFin);
        if ($interval->days < 7) {
            return response()->json(['errors' => ['fechaFin' => ['El rango de fechas debe ser de al menos una semana.']]], 422);
        }

        // Actualizar la sección
        $seccion = Seccion::findOrFail($id);
        $seccion->update([
            'idServicio' => $request->idServicio,
            'fechaInicio' => $request->fechaInicio,
            'fechaFin' => $request->fechaFin,
            'horaInicio' => $horaInicio->format('H:i'),
            'horaFin' => $horaFin->format('H:i'),
            'capacidad' => $servicio->capacidadMaxima,
            'idEntrenador' => $request->idEntrenador,
        ]);

        // Actualizar los días seleccionados
        $seccion->dias()->sync($request->dias);

        return response()->json(['success' => 'Sección actualizada exitosamente.']);
    }


    public function destroy($id)
    {
        $seccion = Seccion::findOrFail($id);
        $seccion->update(['eliminado' => 0]);

        return response()->json(['success' => 'Sección eliminada exitosamente.']);
    }
}
