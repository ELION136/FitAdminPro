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
        'nombre',
        'primerApellido',
        'segundoApellido',
        'telefono',
        'descripcion',
        'especialidad',
        'genero',
        'image',
        'fechaNacimiento',
        'fechaContratacion',
        'fechaCreacion',
        'fechaModificacion',
        'eliminado',
    ];
    protected $casts = [
        'fechaNacimiento' => 'date',
        'fechaContratacion' => 'date',
        'fechaCreacion' => 'datetime',
        'fechaModificacion' => 'datetime',
    ];
    
    protected $dates = ['fechaContratacion','fechaNacimiento' , 'fechaCreacion', 'fechaModificacion'];

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'idEntrenador');
    }

}
