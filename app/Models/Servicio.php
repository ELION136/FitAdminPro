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
        'precio',
        'fechaInicio',     // Añadir el campo fechaInicio
        'fechaFin',   
        'idAutor',
        'eliminado',
    ];

    public $timestamps = false;

    protected $dates = [
        'fechaCreacion',
        'fechaModificacion',
        'fechaInicio',     // Indicar que es un campo de tipo fecha
        'fechaFin', ];
 
        protected $casts = [
            'fechaInicio' => 'date',
            'fechaFin' => 'date',
        ];
    // Relaciones
    public function horarios()
    {
        return $this->hasMany(Horario::class, 'idServicio');
    }
}
