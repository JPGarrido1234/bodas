<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activity';

    protected $fillable = ['boda_id', 'description', 'user_id']; 

    /**
     * Creación boda
     * Detalles boda modificados
     * Formulario completar datos personales
     * Novios completan datos personales
     * Contrato enviado
     * Contrato firmado
     * Documento enviado
     * Grupo de ofertas gastr. seleccionado
     * Prueba de menú enviada
     * Prueba de menú seleccionada
     * Selección final enviada
     * Selección final seleccionada
     * Plano seleccionado
    **/

    public function boda() {
        return $this->belongsTo('App\Models\Boda', 'boda_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
