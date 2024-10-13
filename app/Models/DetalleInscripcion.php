<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleInscripcion extends Model
{
    use HasFactory;

    protected $table = 'detalle_inscripciones';
    protected $primaryKey = 'idDetalle';

    protected $fillable = [
        'idInscripcion',
        'tipoProducto',
        'idMembresia',
        'idSeccion',
        'precio',
        'cantidadSecciones',
        'descuento'
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'idInscripcion', 'idInscripcion');
    }

    public function membresia()
    {
        return $this->belongsTo(Membresia::class, 'idMembresia', 'idMembresia');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idServicio', 'idServicio');
    }


    public function seccion()
    {
        return $this->belongsTo(Seccion::class, 'idSeccion', 'idSeccion');
    }
}
