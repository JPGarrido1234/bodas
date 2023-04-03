<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Selecciones_comercial_oferta_gastronomica extends Model
{
    use HasFactory;

    protected $table = 'selecciones_comercial_oferta_gastronomica';

    protected $fillable = [
        'id_boda',
        'selecciones',
        'type'
    ];

    protected $with = ['seleccion_usuario'];

    public function boda() {
        return $this->belongsTo('App\Models\Boda', 'id_boda');
    }

    public function seleccion_usuario() {
        return $this->hasOne('App\Models\Selecciones_usuario_oferta_gastronomica', 'id_seleccion_com');
    }

    public function getNumeroProductosAttribute() {
        $cont = 0;
        foreach (unserialize($this->selecciones) as $categoria) {
            foreach ($categoria as $producto) {
                $cont++;
            }
        }

        return $cont;
    }

    public function getSeleccionNombresAttribute() {
        $retorno = [];
        foreach (unserialize($this->selecciones) as $categoria) {
            foreach ($categoria as $producto) {
                $retorno[Categorias_oferta_gastronomica::find($categoria)->nombre] = Productos_oferta_gastronomica::find($producto)->nombre;
            }
        }

        return $retorno;
    }
}
