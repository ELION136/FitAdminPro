<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';
    protected $primaryKey = 'idServicio';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'idCategoria',       // Clave foránea hacia la tabla categorias_servicios
        'capacidad',         // Capacidad del servicio
        'precioTotal',       // Precio total del servicio
        'cantidadSesiones',
                // Nueva hora de fin
        'duracion',          // Duración del servicio
        'estado',
        'idEntrenador',
        'idAutor',         // Autor que creó o modificó el servicio
        'eliminado'
    ];

    protected $casts = [
        'fechaCreacion' => 'datetime',
        'fechaModificacion' => 'datetime',
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaServicio::class, 'idCategoria')->withDefault(); // Relación con categorías de servicios
    }

    public function entrenador()
    {
        return $this->belongsTo(Entrenador::class, 'idEntrenador')->withDefault(); // Relación con entrenadores
    }


    public function diasSemana()
    {
        return $this->belongsToMany(DiaSemana::class, 'servicio_dias_horarios', 'idServicio', 'idDia')
                    ->withPivot('horaInicio', 'horaFin');
    }

    public function detallesInscripciones()
    {
        return $this->hasMany(DetalleInscripcion::class, 'idServicio');
    }
    public function diasHorarios()
{
    return $this->hasMany(ServicioDiaHorario::class, 'idServicio', 'idServicio');
}

}
