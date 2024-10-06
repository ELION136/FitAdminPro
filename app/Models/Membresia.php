<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    use HasFactory;

    protected $table = 'membresias';
    protected $primaryKey = 'idMembresia';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 
        'precio', 
        'duracion', 
        'descripcion',
        'idAutor',
        'eliminado',
    ];
    protected $dates = ['fechaCreacion', 'fechaModificacion'];

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'idMembresia');
    }
}
