<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleServicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'servicio_id',
        'detalle',
        'urlVideo',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}
