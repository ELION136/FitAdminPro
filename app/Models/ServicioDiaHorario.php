<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioDiaHorario extends Model
{
    protected $table = 'servicio_dias_horarios';
    public $timestamps = false; // Si tu tabla no tiene timestamps
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'idServicio',
        'idDia',
        'horaInicio',
        'horaFin',
    ];

    /**
     * Relación inversa con el modelo Servicio.
     */
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idServicio', 'idServicio');
    }

    /**
     * Relación con el modelo DiaSemana.
     */
    public function diaSemana()
    {
        return $this->belongsTo(DiaSemana::class, 'idDia', 'idDia');
    }
}
