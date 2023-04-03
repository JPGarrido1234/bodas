<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos_oferta_gastronomica extends Model
{
    use HasFactory;

    protected $table = 'productos_oferta_gastronomica';

    protected $fillable = [
        'nombre',
        'id_categoria',
        'id_subcategoria',
        'visible',
    ];

    public function categoria() {
        return $this->hasOne('App\Models\Categorias_oferta_gastronomica', 'id', 'id_categoria');
    }

    public function categorias() {
        return $this->belongsToMany('App\Models\Categorias_oferta_gastronomica', 'categorias_productos', 'id_producto', 'id_categoria');
    }

    public function getMostrarCategoriasAttribute() {
        $categorias = $this->categorias->toArray();
        if($categorias == null){ return null; }
        foreach($categorias as $key => $cat) {
            echo $cat['nombre'];
            if ($key != array_key_last($categorias)) {
                echo ' | ';
            }
        }
    }
}
