<?php

namespace App\Exports;

use App\Models\Reserva;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;

class ReservasExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $reservas;

    public function __construct($reservas)
    {
        $this->reservas = $reservas;
    }

    public function collection()
    {
        return Reserva::with(['cliente', 'detalleReservas.horario.servicio', 'detalleReservas.horario.entrenador'])
        ->get()
        ->map(function ($reserva) {
            return [
                'Nro Reserva' => $reserva->idReserva,
                'Cliente' => $reserva->cliente->nombre,
                'Servicio' => $reserva->detalleReservas->first()->horario->servicio->nombre,
                'Horario' => $reserva->detalleReservas->first()->horario->diaSemana . ' ' .
                    $reserva->detalleReservas->first()->horario->horaInicio . ' - ' .
                    $reserva->detalleReservas->first()->horario->horaFin,
                'Entrenador' => $reserva->detalleReservas->first()->horario->entrenador->nombre,
                
                // Conversión manual de fecha a Carbon antes de formatear
                'Fecha Reserva' => Carbon::parse($reserva->fechaReserva)->format('d/m/Y'),

                'Estado' => $reserva->estado,
                'Total Pagado' => $reserva->total,
                'Estado de Pago' => $reserva->estadoPago,
            ];
        });
    }
}
