<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocSigned extends Model
{
    use HasFactory;

    protected $table = 'bodas_docs';

    protected $fillable = ['token', 'boda_id', 'doc_id', 'firmado'];

    protected $appends = ['url', 'storage_url', 'signed_url', 'signed_storage_url'];

    public function boda() {
        return $this->belongsTo('App\Models\Boda', 'boda_id');
    }

    public function doc() {
        return $this->belongsTo('App\Models\Doc', 'doc_id');
    }

    /** CUSTOM **/

    public function getUrlAttribute() {
        return '/storage/signs/'.$this->boda_id.'/'.$this->doc_id.'.pdf';
    }

    public function getStorageUrlAttribute() {
        return '/public/signs/'.$this->boda_id.'/'.$this->doc_id.'.pdf';
    }

    public function getSignedUrlAttribute() {
        return '/storage/signs/'.$this->boda_id.'/'.$this->doc_id.'_signed.pdf';
    }

    public function getSignedStorageUrlAttribute() {
        return '/public/signs/'.$this->boda_id.'/'.$this->doc_id.'_signed.pdf';
    }
}
