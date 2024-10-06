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
        'idUsuario',
        'nombre',
        'primerApellido',
        'segundoApellido',
        'fechaNacimiento',
        'genero',
        'direccion',
        'telefonoEmergencia',
        'idAutor',
        'eliminado',
    ];
    protected $casts = [
        'fechaNacimiento' => 'date',
        'fechaCreacion' => 'datetime',
        'fechaModificacion' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    public function membresias()
    {
        return $this->hasMany(Membresia::class, 'idCliente');
    }

    public function asistencia()
    {
        return $this->hasMany(Asistencia::class, 'idCliente', 'idCliente');
    }

    public function asistenciasMes()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        return $this->asistencia()->where('fecha', '>=', $inicioMes)->count();
    }

    // Método para obtener asistencias del año
    public function asistenciasAnio()
    {
        $inicioAnio = Carbon::now()->startOfYear();
        return $this->asistencia()->where('fecha', '>=', $inicioAnio)->count();
    }
    
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'idCliente');
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
