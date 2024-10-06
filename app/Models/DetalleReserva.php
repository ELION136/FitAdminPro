<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleReserva extends Model
{
    use HasFactory;

    protected $table = 'detalle_reservas';
    protected $primaryKey = 'idDetalleReserva';

    public $timestamps = false;

    protected $fillable = [
        'idReserva',
        'idHorario',
        'cantidad',
        'precioUnitario',
        'subtotal'
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'idReserva');
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class, 'idHorario');
    }
}
