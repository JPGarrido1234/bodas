<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'messages';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'message',
        'attachment',
        'readed',
        'chat_id',
        'user_id'
    ];

    public static $rules = array(
        'message' => 'required'
     );
     public static function validate($data){
        $reglas = self::$rules;
        return Validator::make($data, $reglas);
     }

    public function chat() {
        return $this->belongsTo('App\Models\Chat', 'chat_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
