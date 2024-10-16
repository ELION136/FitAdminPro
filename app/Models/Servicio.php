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
        'incluyeCostoEntrada',
        'horaInicio',        // Nueva hora de inicio
        'horaFin',           // Nueva hora de fin
        'duracion',          // Duración del servicio
        'fechaInicio',       // Fecha de inicio del servicio
        'fechaFin',          // Fecha de fin del servicio
        'idEntrenador',      // Clave foránea hacia la tabla entrenadores
        'idAutor',           // Autor que creó o modificó el servicio
        'eliminado'
    ];

    protected $casts = [
        'fechaCreacion' => 'datetime',
        'fechaModificacion' => 'datetime',
        'fechaInicio' => 'date:Y-m-d',
        'fechaFin' => 'date:Y-m-d',
        

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
        return $this->belongsToMany(DiaSemana::class, 'servicio_dias', 'idServicio', 'idDia');
    }

    public function detallesInscripciones()
    {
        return $this->hasMany(DetalleInscripcion::class, 'idServicio');
    }
}
