<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cliente extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'clientes';
    protected $primaryKey = 'idCliente';

    protected $fillable = [
        'nombre',
        'primerApellido',
        'segundoApellido',
        'fechaNacimiento',
        'genero',
        'direccion',
        'telefonoEmergencia',
        'qrCode',
        'image',
        'idAutor',
        'eliminado'
    ];
    protected $casts = [
        'fechaNacimiento' => 'date',
        'fechaCreacion' => 'datetime',
        'fechaModificacion' => 'datetime',
    ];

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'idCliente', 'idCliente');
    }

    // Relación con las asistencias
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'idCliente', 'idCliente');
    }
    /*
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'idCliente');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'idCliente');
    }

    */ 

}
