<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleInscripcion extends Model
{
    use HasFactory;

    protected $table = 'detalle_inscripciones';
    protected $primaryKey = 'idDetalle';
    public $timestamps = false;

    protected $fillable = [
        'idInscripcion',
        'tipoProducto',
        'idMembresia',
        'idServicio',
        'precio',
        'descuento',
        'tipoDescuento',
        'sesionesRestantes'
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'idInscripcion');
    }
    public function membresia()
    {
        return $this->belongsTo(Membresia::class, 'idMembresia');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idServicio');
    }
}
