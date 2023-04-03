<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'datos_facturacion';

    public function boda() {
        return $this->belongsTo('App\Models\Boda', 'boda_id');
    }
}
