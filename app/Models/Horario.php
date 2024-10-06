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
        'idServicio',
        'idEntrenador', 
        'diaSemana',
        'horaInicio',
        'horaFin',
        'capacidad',
        'idAutor',
        'eliminado',
    ];

    public $timestamps = false;

    protected $dates = ['fechaCreacion', 'fechaModificacion'];


    /*public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'idEmpleado');
    }*/public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idServicio');
    }

    public function entrenador()
    {
        return $this->belongsTo(Entrenador::class, 'idEntrenador');
    }

    public function detalleReservas()
    {
        return $this->hasMany(DetalleReserva::class, 'idHorario');
    }


}
