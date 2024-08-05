<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comentario extends Model
{
    use HasFactory;

    protected $table = 'comentarios';
    protected $primaryKey = 'idComentario';

    protected $fillable = [
        'idCliente',
        'calificacion',
        'comentario',
        'fechaComentario',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }
}
