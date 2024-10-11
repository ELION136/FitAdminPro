<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seccion extends Model
{
    use HasFactory;

    protected $table = 'secciones';
    protected $primaryKey = 'idSeccion';
    public $timestamps = false;

    protected $fillable = [
        'idServicio',
        'fechaInicio',
        'fechaFin',
        'horaInicio',
        'horaFin',
        'capacidad',
        'idEntrenador',
        'estado',
        'idAutor',
        'eliminado'
    ];

    protected $casts = [
        'fechaCreacion' => 'datetime',
        'fechaModificacion' => 'datetime',
    ];

    /**
     * Relación con el modelo Servicio.
     */
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idServicio', 'idServicio');
    }

    /**
     * Relación con el modelo Entrenador.
     */
    public function entrenador()
    {
        return $this->belongsTo(Entrenador::class, 'idEntrenador', 'idEntrenador');
    }

    /**
     * Relación muchos a muchos con el modelo DiaSemana.
     */
    public function dias()
    {
        return $this->belongsToMany(DiaSemana::class, 'seccion_dias', 'idSeccion', 'idDia');
    }

    /**
     * Verificar si la sección está activa.
     */
    public function isActive()
    {
        return $this->estado === 'activa';
    }
}
