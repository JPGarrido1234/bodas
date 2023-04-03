<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    use HasFactory;

    protected $table = 'planos';

    protected $with = 'mesas';

    public function mesas() {
        return $this->hasMany('App\Models\Mesa');
    }

    public function place() {
        return $this->belongsTo('App\Models\Place', 'place_id');
    }

    public function getImgAttribute() {
        return '/storage/planos/'.$this->id.'.pdf';
    }

    public function bodas() {
        return $this->hasMany('App\Models\Boda');
    }
}
