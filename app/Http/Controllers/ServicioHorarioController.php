<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\DiaSemana;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ServicioHorarioController extends Controller
{
    public function edit($servicioId)
    {
        $servicio = Servicio::with('diasSemana')->findOrFail($servicioId);
        $diasSemana = DiaSemana::all();

        return view('admin.servicios.horarios.index', compact('servicio', 'diasSemana'));
    }

    public function update(Request $request, $servicioId)
    {
        $validator = Validator::make($request->all(), [
            'idDia' => 'required|array',
            'idDia.*' => 'exists:dias_semana,idDia',
        ]);

        foreach ($request->input('idDia', []) as $dia) {
            $validator->addRules([
                "horaInicio.$dia" => 'required|date_format:H:i',
                "horaFin.$dia" => 'required|date_format:H:i|after:horaInicio.' . $dia
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $servicio = Servicio::findOrFail($servicioId);

        // Detach previous schedules and sync new ones
        $servicio->diasSemana()->detach();

        // Synchronize selected schedules
        $horarios = [];
        foreach ($request->idDia as $dia) {
            $horarios[$dia] = [
                'horaInicio' => $request->horaInicio[$dia],
                'horaFin' => $request->horaFin[$dia]
            ];
        }
        $servicio->diasSemana()->sync($horarios);

        // Update service status to active if at least one schedule is assigned
        if (count($horarios) > 0) {
            $servicio->update(['estado' => 1]);
        }

        return response()->json(['success' => 'Horarios actualizados exitosamente.']);
    }

    public function destroy($servicioId, $diaId, $horaInicio)
    {
        $servicio = Servicio::findOrFail($servicioId);

        // Eliminar horario específico
        $servicio->diasSemana()->wherePivot('idDia', $diaId)
            ->wherePivot('horaInicio', $horaInicio)
            ->detach();

        // Actualizar el estado del servicio a inactivo si no quedan horarios asignados
        if ($servicio->diasSemana()->count() === 0) {
            $servicio->update(['estado' => 0]); // 0 = Inactivo
        }

        return response()->json(['success' => 'Horario eliminado y servicio actualizado si es necesario.']);
    }

}
