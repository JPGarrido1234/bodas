<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ContratoValue;

class ContratoField extends Model
{
    use HasFactory;

    protected $table = 'contratos_fields';

    protected $fillable = ['name', 'doc_id', 'show_name'];

    public $timestamps = false;

    public function values() {
        $this->hasMany('App\Models\ContratoValue');
    }

    public function value($boda_id) {
        return ContratoValue::where('boda_id', $boda_id)->where('field_id', $this->id)->value('value');
    }
}
