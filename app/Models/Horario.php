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
        'fechaCreacion', 
        'fechaModificacion',
        'idAutor',
        'eliminado',
    ];

    public $timestamps = false;

    protected $dates = ['fechaCreacion', 'fechaModificacion'];


    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'idEmpleado');
    }


    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'servicios_horarios', 'idHorario', 'idServicio');
    }



}
