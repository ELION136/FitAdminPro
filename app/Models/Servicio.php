<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';
    protected $primaryKey = 'idServicio';
    protected $fillable = [
        'nombre',
        'descripcion',
        'duracion',
        'tipoServicio',
        'categoria',
        'idAutor',
        'eliminado',
    ];

    public $timestamps = false;

    protected $dates = ['fechaCreacion', 'fechaModificacion'];

    // Relaciones
    public function horarios()
    {
        return $this->belongsToMany(Horario::class, 'servicios_horarios', 'idServicio', 'idHorario');
    }
}
