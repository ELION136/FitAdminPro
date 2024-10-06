<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'reservas';
    protected $primaryKey = 'idReserva';

    protected $fillable = [
        'idCliente', 
        'total', 
        'descuento', 
        'estado',
        'idAutor',
        'eliminado',
    ];

    
    protected $dates = ['fechaReserva', 'fechaCreacion', 'fechaModificacion'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    public function detalleReservas()
    {
        return $this->hasMany(DetalleReserva::class, 'idReserva');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'idReserva');
    }
}
