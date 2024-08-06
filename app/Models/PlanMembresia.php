<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanMembresia extends Model
{
    use HasFactory;

    protected $table = 'planesMembresia';
    protected $primaryKey = 'idPlan';
    public $timestamps = false;

    protected $fillable = [
        'nombrePlan',
        'descripcion',
        'duracion',
        'precio',
        'idAutor',
        'eliminado',
    ];
    protected $casts = [
        'fechaCreacion' => 'datetime',
        'fechaModificacion' => 'datetime',
    ];

    public function membresias()
    {
        return $this->hasMany(Membresia::class, 'idPlan');
    }
}
