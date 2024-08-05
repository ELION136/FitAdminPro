<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    use HasFactory;

    protected $table = 'membresias';
    protected $primaryKey = 'idMembresia';

    protected $fillable = [
        'idCliente',
        'idPlan',
        'fechaInicio',
        'fechaFin',
        'estado',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    public function planMembresia()
    {
        return $this->belongsTo(PlanMembresia::class, 'idPlan');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'idMembresia');
    }
}
