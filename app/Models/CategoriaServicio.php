<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaServicio extends Model
{
    use HasFactory;
    protected $table = 'categorias_servicios';
    protected $primaryKey = 'idCategoria';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'idCategoria');
    }
}
