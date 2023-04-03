<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'rol',
        'boda_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'notificaciones',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function bodas() { // Si es comercial
        return $this->belongsToMany('App\Models\Boda', 'bodas_coms', 'com_id', 'boda_id');
    }

    public function boda() { // Si es user
        return $this->belongsTo('App\Models\Boda', 'boda_id');
    }

    public function getActivitiesAttribute() {
        $bodas = $this->bodas->pluck('id');
        return Activity::whereIn('boda_id', $bodas)->get();
    }

    public function getChatAttribute() {
        return $this->boda->chat;
    }

    public function getChatsAttribute() {
        return Chat::whereIn('boda_id', $this->bodas->pluck('id'));
    }

    public function getNotificacionesAttribute() {
        $array = [];
        $boda = $this->boda;

        // Mensajes
        if(user() && user()->rol == 'user') {
            $array['mensajes'] = $this->chat->new_messages ?? [];
            $array['contratos'] = $boda->contratos->sortByDesc('created_at')->take(1)->where('firmado', 0)->groupBy('doc_id');
            $array['invitados'] = $boda->invitados->whereNull('confirm');
        }

        return (object)$array;
    }
    
}
