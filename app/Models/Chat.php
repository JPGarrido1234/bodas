<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';
    protected $fillable = [
        'boda_id',
    ];

    public function boda() {
        return $this->belongsTo('App\Models\Boda', 'boda_id');
    }

    public function getComsAttribute() {
        return $this->boda->coms;
    }

    public function messages() {
        return $this->hasMany('App\Models\Message', 'chat_id');
    }

    public function getNewMessagesAttribute() {
        return $this->messages->where('readed', 0)->where('user_id', '!=', user()->id);
    }
}
