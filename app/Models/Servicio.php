<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'servicios';
    protected $primaryKey = 'idServicio';

    protected $fillable = [
        'nombre',
        'descripcion',
        'capacidadMaxima',
        'precioPorSeccion',
        'incluyeCostoEntrada',
        'idAutor',
        'eliminado'
    ];

    protected $casts = [
        'fechaCreacion' => 'datetime',
        'fechaModificacion' => 'datetime',
    ];

    public function secciones()
    {
        return $this->hasMany(Seccion::class, 'idServicio', 'idServicio');
    }
}
