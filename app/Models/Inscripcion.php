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
        'fechaCreacion' => 'datetime',
        'fechaModificacion' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente', 'idCliente');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario', 'idUsuario');
    }

    public function detalleInscripciones()
    {
        return $this->hasMany(DetalleInscripcion::class, 'idInscripcion', 'idInscripcion');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'idInscripcion', 'idInscripcion');
    }

    public function isActiva()
    {
        return $this->estado === 'activa' && Carbon::now()->between($this->fechaInicio, $this->fechaFin);
    }

    


}
