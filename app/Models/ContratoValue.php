<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratoValue extends Model
{
    use HasFactory;

    protected $table = 'contratos_values';

    protected $fillable = ['value', 'field_id', 'boda_id', 'doc_id'];

    public $timestamps = false;

    public function field() {
        $this->belongsTo('App\Models\ContratoField', 'field_id');
    }

    public function doc() {
        $this->belongsTo('App\Models\Doc', 'doc_id');
    }

    public function boda() {
        $this->belongsTo('App\Models\Boda', 'boda_id');
    }

}
