<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';
    protected $primaryKey = 'idHorario';

    protected $fillable = [
        'idEmpleado',
        'dia',
        'horaInicio',
        'horaFin',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'idEmpleado');
    }
}
