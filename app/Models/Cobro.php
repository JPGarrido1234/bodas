<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\File;

class Cobro extends Model
{
    use HasFactory;

    protected $table = 'cobros';

    protected $fillable = ['percentage', 'date', 'concepto', 'total', 'type'];

    public function boda() {
        return $this->belongsTo('App\Models\Boda', 'boda_id');
    }

    public function getEstadoAttribute() {
        switch ($this->status) {
            case 'pending':
                return 'Pendiente';
                break;
            case 'completed':
                return 'Completado';
                break;
            
            default:
                return 'Pendiente';
                break;
        }
    }

    public function getJustificanteAttribute() {
        $token = $this->boda->token;
        $path = '/storage/ingreso/'.$token.'/';
        // $files = File::files(storage_path($path));
        $files = File::glob(public_path($path.'*'.$this->id.'*'));

        $file = collect($files)->first();
        $file = basename($file);
        if($file == '') {
            return null;
        }

        $ext = strrchr($file, '.');

        return '/storage/ingreso/'.$token.'/'.$this->id.$ext;
    }
}
