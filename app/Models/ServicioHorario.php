<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioHorario extends Model
{
    use HasFactory;

    protected $table = 'servicios_horarios';
    protected $primaryKey = 'idServicioHorario';
    protected $fillable = [
        'idServicio', 'idHorario', 'idAutor', 
        'fechaCreacion', 'fechaModificacion', 'eliminado'
    ];

    public $timestamps = false;

    protected $dates = ['fechaCreacion', 'fechaModificacion'];

    // Relaciones
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idServicio');
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class, 'idHorario');
    }
}
