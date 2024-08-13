<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{use HasFactory;

    use HasFactory;

    protected $table = 'pagos';
    protected $primaryKey = 'idPago';

    protected $fillable = [
        'idMembresia',
        'monto',
        'fechaPago',
        'metodoPago',
        'fechaCreacion', 
        'fechaModificacion', 
        'idAutor', 
        'eliminado'
    ];

    public $timestamps = false;

    protected $dates = ['fechaPago', 'fechaCreacion', 'fechaModificacion'];

    public function membresia()
    {
        return $this->belongsTo(Membresia::class, 'idMembresia');
    }

}
