<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';
    protected $primaryKey = 'idInscripcion';
    public $timestamps = false;

    protected $fillable = [
        'idCliente',
        'idUsuario',
        'estado',
        'totalPago',
        'diasRestantes',
        'idAutor',
        'eliminado'
    ];

    protected $casts = [
        
        'fechaModificacion' => 'datetime',
        'fechaInscripcion' => 'datetime',

    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    public function detallesInscripciones()
    {
        return $this->hasMany(DetalleInscripcion::class, 'idInscripcion');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'idInscripcion');
    }
    public function isActiva()
    {
        return $this->estado === 'activa' && Carbon::now()->between($this->fechaInicio, $this->fechaFin);
    }

    


}
