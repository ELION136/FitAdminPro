<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Servicio;
use App\Models\ServicioHorario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Entrenador;

class HorarioController extends Controller
{
    public function index()
    {
        // Obtener todos los horarios para mostrarlos en la vista
        $horarios = Horario::with(['servicio', 'entrenador'])->where('eliminado', 1)->get();
        $servicios = Servicio::where('eliminado', 1)->get();
        $entrenadores = Entrenador::all();

        return view('admin.horarios.index', compact('horarios', 'servicios', 'entrenadores'));
    }

    public function store(Request $request)
    {
        // Validar los campos del formulario
        $request->validate([
            'idServicio' => 'required|exists:servicios,idServicio',
            'idEntrenador' => 'required|exists:entrenadores,idEntrenador',
            'diaSemana' => 'required|array',
            'horaInicio' => 'required|date_format:H:i',
            'capacidad' => 'required|integer|min:1',
        ]);

        // Obtener la duración del servicio seleccionado
        $servicio = Servicio::findOrFail($request->idServicio);
        $duracion = $servicio->duracion; // La duración está en minutos

        // Calcular la hora de fin sumando la duración a la hora de inicio
        $horaInicio = Carbon::createFromFormat('H:i', $request->horaInicio);
        $horaFin = $horaInicio->copy()->addMinutes($duracion); // Sumar duración en minutos

        $idAutor = auth()->id();

        // Verificar si ya existe un horario en el mismo día, misma hora, mismo servicio y entrenador
        foreach ($request->diaSemana as $dia) {
            $exists = Horario::where('idServicio', $request->idServicio)
                ->where('idEntrenador', $request->idEntrenador)
                ->where('diaSemana', $dia)
                ->where('horaInicio', $request->horaInicio)
                ->where('horaFin', $horaFin->format('H:i'))
                ->exists();

            if ($exists) {
                return back()->withErrors([
                    'error' => "Ya existe un horario para el servicio y entrenador en $dia a la misma hora."
                ]);
            }

            // Si no existe, crear un nuevo registro para ese día
            Horario::create([
                'idServicio' => $request->idServicio,
                'idEntrenador' => $request->idEntrenador,
                'diaSemana' => $dia,
                'horaInicio' => $request->horaInicio,
                'horaFin' => $horaFin->format('H:i'), // Guardar la horaFin calculada
                'capacidad' => $request->capacidad,
                'idAutor' => $idAutor,
            ]);
        }

        return redirect()->route('admin.horarios.index')->with('success', 'Horarios creados exitosamente.');
    }

    public function update(Request $request, $id)
    {
        // Validar los datos entrantes
        $request->validate([
            'idServicio' => 'required|exists:servicios,idServicio',
            'idEntrenador' => 'required|exists:entrenadores,idEntrenador',
            'diaSemana' => 'required|array', // Validar como array
            'diaSemana.*' => 'in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo', // Validar cada día
            'horaInicio' => 'required|date_format:H:i',
            'capacidad' => 'required|integer|min:1',
        ]);

        // Obtener la duración del servicio seleccionado
        $servicio = Servicio::findOrFail($request->idServicio);
        $duracion = $servicio->duracion; // La duración está en minutos

        // Calcular la hora de fin sumando la duración a la hora de inicio
        try {
            $horaInicio = Carbon::createFromFormat('H:i', $request->horaInicio);
        } catch (\Exception $e) {
            return back()->withErrors(['horaInicio' => 'El formato de la hora de inicio no es válido.']);
        }

        $horaFin = $horaInicio->copy()->addMinutes($duracion); // Sumar duración en minutos

        // Buscar el horario existente que se va a actualizar
        $horario = Horario::findOrFail($id);
        $idAutor = auth()->id();

        // Verificar conflictos de horario para cada día seleccionado
        foreach ($request->diaSemana as $dia) {
            $exists = Horario::where('idServicio', $request->idServicio)
                ->where('idEntrenador', $request->idEntrenador)
                ->where('diaSemana', $dia)
                ->where('horaInicio', $request->horaInicio)
                ->where('horaFin', $horaFin->format('H:i'))
                ->where('idHorario', '!=', $horario->idHorario) // Ignorar el horario que estamos actualizando
                ->exists();

            if ($exists) {
                return back()->withErrors([
                    'error' => "Ya existe un horario para el servicio y entrenador en $dia a la misma hora."
                ]);
            }
        }

        // Actualizar el horario en la base de datos
        $horario->idServicio = $request->idServicio;
        $horario->idEntrenador = $request->idEntrenador;
        $horario->horaInicio = $request->horaInicio;
        $horario->horaFin = $horaFin->format('H:i'); // Guardar la horaFin calculada
        $horario->capacidad = $request->capacidad;
        $horario->idAutor = $idAutor;

        // Actualizar los días de la semana, dependiendo si es relación o columna
        $horario->diaSemana = implode(',', $request->diaSemana); // Guardar los días como un string
        $horario->save();

        return redirect()->route('admin.horarios.index')->with('success', 'Horario actualizado exitosamente.');
    }


    public function destroy($id)
    {
        // Borrar de manera lógica (actualizando el campo eliminado a 0)
        $horario = Horario::findOrFail($id);
        $horario->update(['eliminado' => 0]);

        return redirect()->route('admin.horarios.index')->with('success', 'Horario desactivado con éxito.');
    }

}
