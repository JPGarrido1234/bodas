<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nombregrupoinvitado extends Model
{
    use HasFactory;

    public $fillable = ['name_grupo'];
    public $timestamps = false;

    public function grupoinvitados() {
        return $this->hasMany('App\Models\GrupoInvitados', 'name_grupo_id');
    }
}