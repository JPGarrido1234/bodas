<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iksaku\Laravel\MassUpdate\MassUpdatable;

class Mesa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'mesas';

    public function plano() {
        return $this->belongsTo('App\Models\Plano', 'plano_id');
    }

    public function invitados() {
        return $this->hasMany('App\Models\Invitado');
    }

    public function invitados_boda($boda_id = null) {
        if($boda_id == null) { $boda_id = auth()->user()->boda->id; }
        return $this->hasMany('App\Models\Invitado')->where('boda_id', $boda_id)->get();
    }
}
