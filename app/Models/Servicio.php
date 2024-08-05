<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'empleado_id',
        'cliente_id',
        'tipo',
        'nombreServicio',
        'descripcion',
        'fechaInicio',
        'fechaFin',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleServicio::class);
    }
}
