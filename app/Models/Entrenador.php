<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrenador extends Model
{
    use HasFactory;

     
    public $timestamps = false;
    protected $table = 'entrenadores'; // en este caso posible mente cambie a entrenador
    protected $primaryKey = 'idEntrenador';  //idEntrenador

    protected $fillable = [
        'idUsuario',
        'nombre',
        'primerApellido',
        'segundoApellido',
        'fechaNacimiento',
        'genero',
        'fechaContratacion',
        'direccion',
        'especialidad',
        'descripcion',
        'idAutor',
        'eliminado',
    ];
    protected $casts = [
        'fechaNacimiento' => 'date',
        'fechaContratacion' => 'date',
        'fechaCreacion' => 'datetime',
        'fechaModificacion' => 'datetime',
    ];


    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'idEntrenador');
    }

}
