<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaSemana extends Model
{
    use HasFactory;

    protected $table = 'dias_semana';
    protected $primaryKey = 'idDia';
    public $timestamps = false;

    protected $fillable = [
        'nombreDia'
    ];

    /**
     * Relación muchos a muchos con el modelo Seccion.
     */
    public function secciones()
    {
        return $this->belongsToMany(Seccion::class, 'seccion_dias', 'idDia', 'idSeccion');
    }
}
