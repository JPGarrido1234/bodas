<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Selecciones_usuario_oferta_gastronomica extends Model
{
    use HasFactory;

    protected $table = 'selecciones_usuario_oferta_gastronomica';

    protected $fillable = [
        'id_seleccion_com',
        'selecciones',
        'type'
    ];

    // protected $appends = ['boda'];

    public function selecciones_comercial() {
        return $this->belongsTo('App\Models\Selecciones_comercial_oferta_gastronomica', 'id_seleccion_com');
    }

    public function getValuesAttribute() {
        return call_user_func_array('array_merge', unserialize($this->selecciones));
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
