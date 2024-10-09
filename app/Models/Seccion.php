<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seccion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'secciones';
    protected $primaryKey = 'idSeccion';

    protected $fillable = [
        'idServicio',
        'fechaInicio',
        'fechaFin',
        'horaInicio',
        'horaFin',
        'capacidad',
        'idEntrenador',
        'idAutor',
        'eliminado'
    ];

    protected $casts = [
        'fechaCreacion' => 'datetime',
        'fechaModificacion' => 'datetime',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idServicio', 'idServicio');
    }

    public function entrenador()
    {
        return $this->belongsTo(Entrenador::class, 'idEntrenador', 'idEntrenador');
    }

    public function inscripciones()
    {
        return $this->hasMany(DetalleInscripcion::class, 'idSeccion', 'idSeccion');
    }
}
