<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use Carbon\Carbon;
use App\Models\DiaSemana;
use App\Models\Entrenador;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class ServiciosController extends Controller
{
    public function index()
    {
        $servicios = Servicio::where('eliminado', 1)
            ->with('diasSemana')
            ->get();
        $entrenadores = Entrenador::where('eliminado', 1)->get();
        $diasSemana = DiaSemana::all();

        return view('admin.servicios.index', compact('servicios', 'entrenadores', 'diasSemana'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'               => 'required|string|max:50',
            'descripcion'          => 'nullable|string',
            'capacidad'            => 'required|integer|min:1',
            'precioTotal'          => 'required|numeric|min:0|max:10000',
            'horaInicio'           => 'required|date_format:H:i',
            'duracion'             => 'required|integer|min:60|max:1440',
            'incluyeCostoEntrada'  => 'boolean',
            'fechaInicio'          => 'required|date',
            'fechaFin'             => 'required|date|after_or_equal:fechaInicio',
            'idEntrenador'         => 'required|exists:entrenadores,idEntrenador',
            'idDia'                => 'required|array',
            'idDia.*'              => 'exists:dias_semana,idDia'
        ]);

        $validator->after(function ($validator) use ($request) {
            $fechaInicio = Carbon::parse($request->fechaInicio);
            $fechaFin = Carbon::parse($request->fechaFin);
            $hoy = Carbon::today();

            // Validar que la fecha de inicio sea al menos una semana después de hoy
            if ($fechaInicio->lt($hoy)) {
                $validator->errors()->add('fechaInicio', 'La fecha de inicio debe ser a partir de hoy.');
            }

            // Validar que haya al menos una semana entre fechaInicio y fechaFin
            if ($fechaFin->lt($fechaInicio->copy()->addWeek())) {
                $validator->errors()->add('fechaFin', 'La fecha de fin debe ser al menos una semana después de la fecha de inicio.');
            }

            // Validaciones de hora de inicio y fin
            $horaApertura = Carbon::createFromFormat('H:i', '06:00');
            $horaCierre = Carbon::createFromFormat('H:i', '22:00');
            $horaInicio = Carbon::createFromFormat('H:i', $request->horaInicio);
            $duracion = $request->duracion;
            $horaFin = $horaInicio->copy()->addMinutes($duracion);

            // Validar que la hora de inicio esté dentro del horario del gimnasio
            if ($horaInicio->lt($horaApertura) || $horaInicio->gte($horaCierre)) {
                $validator->errors()->add('horaInicio', 'La hora de inicio debe estar entre las 06:00 y las 22:00.');
            }

            // Validar que la hora de fin no exceda el horario del gimnasio
            if ($horaFin->gt($horaCierre)) {
                $validator->errors()->add('horaFin', 'La hora de fin no puede exceder las 22:00.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Calcular la hora de fin
        $horaFin = Carbon::createFromFormat('H:i', $request->horaInicio)
            ->addMinutes($request->duracion)
            ->format('H:i');

        $servicio = Servicio::create([
            'nombre'               => $request->nombre,
            'descripcion'          => $request->descripcion,
            'capacidad'            => $request->capacidad,
            'precioTotal'          => $request->precioTotal,
            'horaInicio'           => $request->horaInicio,
            'horaFin'              => $horaFin,
            'duracion'             => $request->duracion,
            'fechaInicio'          => $request->fechaInicio,
            'fechaFin'             => $request->fechaFin,
            'incluyeCostoEntrada'  => $request->incluyeCostoEntrada ? 1 : 0,
            'idEntrenador'         => $request->idEntrenador,
            'idAutor'              => Auth::id(),
            'eliminado'            => 1,
        ]);

        // Sincronizar los días seleccionados
        $servicio->diasSemana()->sync($request->idDia);

        return response()->json(['success' => 'Servicio creado exitosamente.']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre'               => 'required|string|max:50',
            'descripcion'          => 'nullable|string',
            'capacidad'            => 'required|integer|min:1',
            'precioTotal'          => 'required|numeric|min:0|max:10000',
            'horaInicio'           => 'required|date_format:H:i',
            'duracion'             => 'required|integer|min:60|max:1440',
            'incluyeCostoEntrada'  => 'boolean',
            'fechaInicio'          => 'required|date',
            'fechaFin'             => 'required|date|after_or_equal:fechaInicio',
            'idEntrenador'         => 'required|exists:entrenadores,idEntrenador',
            'idDia'                => 'required|array',
            'idDia.*'              => 'exists:dias_semana,idDia'
        ]);

        $validator->after(function ($validator) use ($request) {
            $fechaInicio = Carbon::parse($request->fechaInicio);
            $fechaFin = Carbon::parse($request->fechaFin);
            $hoy = Carbon::today();

            // Validar que la fecha de inicio sea al menos una semana después de hoy
            if ($fechaInicio->lt($hoy)) {
                $validator->errors()->add('fechaInicio', 'La fecha de inicio debe ser a partir de hoy.');
            }

            // Validar que haya al menos una semana entre fechaInicio y fechaFin
            if ($fechaFin->lt($fechaInicio->copy()->addWeek())) {
                $validator->errors()->add('fechaFin', 'La fecha de fin debe ser al menos una semana después de la fecha de inicio.');
            }

            // Validaciones de hora de inicio y fin
            $horaApertura = Carbon::createFromFormat('H:i', '06:00');
            $horaCierre = Carbon::createFromFormat('H:i', '22:00');
            $horaInicio = Carbon::createFromFormat('H:i', $request->horaInicio);
            $duracion = $request->duracion;
            $horaFin = $horaInicio->copy()->addMinutes($duracion);

            // Validar que la hora de inicio esté dentro del horario del gimnasio
            if ($horaInicio->lt($horaApertura) || $horaInicio->gte($horaCierre)) {
                $validator->errors()->add('horaInicio', 'La hora de inicio debe estar entre las 06:00 y las 22:00.');
            }

            // Validar que la hora de fin no exceda el horario del gimnasio
            if ($horaFin->gt($horaCierre)) {
                $validator->errors()->add('horaFin', 'La hora de fin no puede exceder las 22:00.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $servicio = Servicio::findOrFail($id);

        // Calcular la hora de fin
        $horaFin = Carbon::createFromFormat('H:i', $request->horaInicio)
            ->addMinutes($request->duracion)
            ->format('H:i');

        $servicio->update([
            'nombre'               => $request->nombre,
            'descripcion'          => $request->descripcion,
            'capacidad'            => $request->capacidad,
            'precioTotal'          => $request->precioTotal,
            'horaInicio'           => $request->horaInicio,
            'horaFin'              => $horaFin,
            'duracion'             => $request->duracion,
            'fechaInicio'          => $request->fechaInicio,
            'fechaFin'             => $request->fechaFin,
            'incluyeCostoEntrada'  => $request->incluyeCostoEntrada ? 1 : 0,
            'idEntrenador'         => $request->idEntrenador,
            'idAutor'              => Auth::id(),
        ]);

        // Sincronizar los días seleccionados
        $servicio->diasSemana()->sync($request->idDia);

        return response()->json(['success' => 'Servicio actualizado exitosamente.']);
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);

        // Marcar como eliminado (asumiendo que 'eliminado' es un campo en la tabla)
        $servicio->update(['eliminado' => 0]);

        return response()->json(['success' => 'Servicio eliminado exitosamente.']);
    }
}
