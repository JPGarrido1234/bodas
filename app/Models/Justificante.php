<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\File;

class Justificante extends Model
{
    use HasFactory;

    protected $table = 'justificantes';

    // protected $appends = ['url'];

    public function cobro() {
        return $this->belongsTo('App\Models\Factura', 'cobro_id');
    }

    public function getUrlAttribute() {
        $token = $this->cobro->boda->token;
        $path = '/storage/ingreso/'.$token.'/'.$this->cobro->id.'/';
        // $files = File::files(storage_path($path));
        $files = File::glob(public_path($path.'*'.$this->id.'*'));

        $file = collect($files)->first();
        $file = basename($file);
        if($file == '') {
            return null;
        }

        $ext = strrchr($file, '.');
        return '/storage/ingreso/'.$token.'/'.$this->cobro->id.'/'.$this->id.$ext;
    }
}