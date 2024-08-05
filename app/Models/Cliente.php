<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany(Asistencia::class, 'idCliente');
    }

}
