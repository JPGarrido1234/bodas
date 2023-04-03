<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoOferta extends Model
{
    use HasFactory;

    protected $table = 'grupos_ofertas';

    protected $fillable = ['name', 'value'];
    
}
