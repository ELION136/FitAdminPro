<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    use HasFactory;

    protected $table = 'membresias';
    protected $primaryKey = 'idMembresia';

    protected $fillable = [
        'nombre',
        'descripcion',
        'duracionDias',
        'precio',
        'idAutor',
        'eliminado'
    ];

    protected $casts = [
        'fechaCreacion' => 'datetime',
        'fechaModificacion' => 'datetime',
    ];

    public function inscripciones()
    {
        return $this->hasMany(DetalleInscripcion::class, 'idMembresia', 'idMembresia');
    }
}
