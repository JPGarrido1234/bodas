<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $table = 'places';

    public function docs() {
        return $this->belongsToMany(Doc::class, 'docs_places', 'place_id', 'doc_id');
    }

    public function planos() {
        return $this->hasMany('App\Models\Plano');
    }
}
