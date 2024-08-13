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
        'nombre', 'codigo', 'descripcion', 'precioMensual', 
        'precioSemanal', 'precioSeccion', 'idAutor', 
        'fechaCreacion', 'fechaModificacion', 'eliminado'
    ];

    public $timestamps = false;

    protected $dates = ['fechaCreacion', 'fechaModificacion'];

    // Relaciones
    public function horarios()
    {
        return $this->belongsToMany(Horario::class, 'servicios_horarios', 'idServicio', 'idHorario');
    }
}
