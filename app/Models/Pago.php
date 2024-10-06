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
        'idReserva',
        'monto',
        
        'estadoPago',
        'fechaCreacion', 
        'fechaModificacion', 
        'idAutor', 
        'eliminado'
    ];

    public $timestamps = false;

    protected $dates = ['fechaPago', 'fechaCreacion', 'fechaModificacion'];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'idReserva');
    }

}
