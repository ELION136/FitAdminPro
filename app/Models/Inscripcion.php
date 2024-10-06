<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';
    protected $primaryKey = 'idInscripcion';
    public $timestamps = false;

    protected $fillable = [
        'idCliente', 
        'idMembresia', 
        'fechaInicio', 
        'fechaFin', 
        'estado',
        'montoPago', 
        'fechaPago', 
        'estadoPago',
        'idAutor',
        'eliminado',
    ];

    protected $dates = ['fechaInicio',
    'fechaFin', 'fechaPago', 
    'fechaCreacion', 'fechaModificacion'];
    protected $casts = [
        'fechaInicio' => 'date',
        'fechaFin' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    public function membresia()
    {
        return $this->belongsTo(Membresia::class, 'idMembresia');
    }

    public function isActiva()
    {
        return $this->estado === 'activa' && Carbon::now()->between($this->fechaInicio, $this->fechaFin);
    }


}
