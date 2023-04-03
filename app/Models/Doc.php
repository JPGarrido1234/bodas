<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;


class Doc extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'docs';

    protected $fillable = [
        'name',
        'boda_id',
        'type',
        'category_id'
    ];

    protected $appends = ['url', 'storage_url', 'size', 'file_exists'];

    public function getUrlAttribute() {
        $folder = ($this->category_id == 1) ? 'contratos' : 'docs';
        $ext = ($this->category_id == 1) ? '.docx' : '.pdf';
        //return '/storage/docs/'.$this->id.'.pdf';
        return '/storage/' . $folder . '/' . $this->id . $ext;
    }

    public function getStorageUrlAttribute() {
        $folder = ($this->category_id == 1) ? 'contratos' : 'docs';
        $ext = ($this->category_id == 1) ? '.docx' : '.pdf';
        return '/public/' . $folder . '/' . $this->id . $ext;
    }

    public function getPublicUrlAttribute() {
        return self::getUrlAttribute();
    }

    public function getPdfUrlAttribute() {
        $folder = ($this->category_id == 1) ? 'contratos' : 'docs';
        return '/storage/'.$folder.'/'.$this->id.'.pdf';
    }

    public function getFileExistsAttribute() {
        $check = Storage::exists($this->storage_url);
        return $check;
    }

    public function fields() {
        return $this->hasMany('App\Models\ContratoField', 'doc_id');
    }

    public function firma($boda_id) {
        return DocSigned::where('doc_id', $this->id)->where('boda_id', $boda_id)->orderBy('created_at', 'desc')->first();
    }

    public function firmado($boda_id) {
        $check = DocSigned::where('doc_id', $this->id)->where('boda_id', $boda_id)->orderBy('created_at', 'desc')->first();
        if($check != '[]' && $check != null) {
            return $check->firmado;
        } else {
            return 0;
        }
    }

    public function enviado($boda_id) {
        $check = DocSigned::where('doc_id', $this->id)->where('boda_id', $boda_id)->orderBy('created_at', 'desc')->first();
        if($check != null) {
            return $check;
        } else {
            return false;
        }
    }

    public function places() {
        return $this->belongsToMany('App\Models\Place', 'docs_places');
    }

    public function getSizeAttribute() {
        if(!file_exists($this->storage_url))
            return '';
            
        $bytes = Storage::size($this->storage_url);

        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
    
}
