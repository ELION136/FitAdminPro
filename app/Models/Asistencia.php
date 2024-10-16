<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';
    protected $primaryKey = 'idAsistencia';
    public $timestamps = false;

    protected $fillable = [
        'idCliente',
        'idInscripcion',
        'metodoRegistro',
        'idAutor',
        'eliminado'
    ];

    protected $casts = [
        'fechaCreacion' => 'datetime',
        'fechaModificacion' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'idInscripcion');
    }
}
