<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoInvitados extends Model
{
    use HasFactory;

    protected $table = 'grupos_invitados';
    public $fillable = ['name_grupo_id'];
    public $timestamps = false;

    public function boda() {
        return $this->belongsTo('App\Models\Boda', 'boda_id');
    }

    public function invitados() {
        return $this->hasMany('App\Models\Invitado', 'grupo_id');
    }

    public function namegrupo(){
        return $this->belongsTo('App\Models\Nombregrupoinvitados', 'id');
    }
}
