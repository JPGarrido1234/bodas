<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitado extends Model
{
    use HasFactory;

    protected $table = 'invitados';

    public $fillable = ['mesa_id'];

    public function grupo() {
        return $this->belongsTo('App\Models\GrupoInvitados', 'grupo_id');
    }

    public function boda() {
        return $this->belongsTo('App\Models\Boda', 'boda_id');
    }

    public function mesa() {
        return $this->belongsTo('App\Models\Mesa', 'mesa_id');
    }

    public function showAlergenos() {
        $ids = unserialize($this->alergenos);
        $result = null;
        if($ids != null) {
            $alers = Alergeno::whereIn('id',$ids)->pluck('name')->toArray();
            $result = implode(', ', $alers);
        }

        return $result;
    }

    public function getFullNameAttribute() {
        return $this->name . ' ' . $this->apellidos;
    }
}
