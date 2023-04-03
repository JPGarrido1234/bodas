<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorias_oferta_gastronomica extends Model
{
    use HasFactory;

    protected $table = 'categorias_oferta_gastronomica';

    protected $fillable = [
        'nombre',
    ];
    
    public function productos() {
        //return $this->hasMany('App\Models\Productos_oferta_gastronomica', 'id_categoria');
        return $this->belongsToMany('App\Models\Productos_oferta_gastronomica', 'categorias_productos', 'id_categoria', 'id_producto');
    }
}
