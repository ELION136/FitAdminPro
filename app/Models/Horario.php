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
        'idEntrenador',
        'dia',
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
    }*/
    public function entrenador()
    {
        return $this->belongsTo(Entrenador::class, 'idEntrenador');
    }


    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'servicios_horarios', 'idHorario', 'idServicio');
    }



}
