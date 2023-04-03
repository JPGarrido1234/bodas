<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosFacturacion extends Model
{
    use HasFactory;

    protected $table = 'datos_facturacion';

    protected $fillable = ['name', 'nif', 'address', 'city', 'country', 'cp', 'email', 'tlf', 'percentage', 'boda_id'];

    public function boda() {
        return $this->belongsTo('App\Models\Boda', 'boda_id');
    }
}
