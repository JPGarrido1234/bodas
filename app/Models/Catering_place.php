<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Catering_place extends Model
{
    use HasFactory;

    protected $table = 'catering_places';

    protected $fillable = [
        'boda_id',
        'valor',
        'name',
    ];
    
    public function boda() { // Si es user
        return $this->belongsTo('App\Models\Boda', 'boda_id');
    }
}
