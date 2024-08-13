<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencia';
    protected $primaryKey = 'idAsistencia';

    protected $fillable = [
        'idCliente',
        'fecha',
        'horaEntrada',
        'horaSalida',
        'idAutor',
        'eliminado',
    ];

    public $timestamps = false;

    protected $dates = ['fecha'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }
}
