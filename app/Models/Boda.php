<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class Boda extends Model
{
    use HasFactory;

    protected $table = 'bodas';

    protected $appends = ['ref', 'progreso', 'ingreso', 'lugar'];

    protected $fillable = [
        'name',
        'tel',
        'reference',
        'email',
        'date',
        'date_ingreso',
        'date_ingreso_recordatorio',
        'year',
        'cubiertos_adultos',
        'cubiertos_ninos',
        'codigo',
        'comida',
        'status_id',
        'menu_enviado',
        'precios_menu',
        'fecha_contacto',
        'notas',
        'token',
        'place_id',
        'grupo_ofertas_id',
        'plano_id',
        'hora_ceremonia',
        'hora_convite',
    ];

    public function comercial() {
        return $this->belongsTo('App\Models\User', 'com_id');
    }

    public function grupoOfertas() {
        return $this->belongsTo('App\Models\GrupoOferta', 'grupo_ofertas_id');
    }

    public function coms() {
        return $this->belongsToMany('App\Models\User', 'bodas_coms', 'boda_id', 'com_id');
    }

    public function place() {
        return $this->belongsTo('App\Models\Place', 'place_id');
    }

    public function files() {
        return $this->belongsToMany('App\Models\File', 'bodas_files', 'boda_id');
    }

    public function docs() {
        return $this->belongsToMany('App\Models\Doc', 'bodas_docs', 'boda_id', 'doc_id');
    }

    public function contratos() {
        return $this->hasMany('App\Models\DocSigned');
    }

    public function activities() {
        return $this->hasMany('App\Models\Activity')->orderBy('created_at', 'desc');
    }

    public function last_activity() {
        $activities = $this->activities;
        if($activities != null) {
            return $this->activities->first();
        } else {
            return null;
        }
    }

    public function all_docs() {
        return $this->hasMany('App\Models\Doc');
    }

    public function valor_lugar_catering() {
        return $this->belongsTo('App\Models\Catering_place', 'boda_id');
    }
    
    public function catering() {
        return $this->hasOne('App\Models\Catering_place', 'boda_id');
    }

    public function getLugarAttribute() {
        if($this->place_id != null && $this->place_id == 3) {
            if($this->catering != null) {
                return $this->catering->name.': '.$this->catering->valor;
            }
        } else {
            return $this->place->name;
        }
    }

    public function selecciones_comercial_oferta_gastronomica() {
        return $this->hasMany('App\Models\Selecciones_comercial_oferta_gastronomica', 'id_boda');
    }

    public function grupos() {
        return $this->hasMany('App\Models\GrupoInvitados');
    }

    public function invitados() {
        return $this->hasMany('App\Models\Invitado');
    }

    public function mesas() {
        return $this->hasMany('App\Models\Mesa');
    }

    public function datos_facturacion() {
        return $this->hasMany('App\Models\DatosFacturacion');
    }
    
    public function plano() {
        return $this->belongsTo('App\Models\Plano', 'plano_id');
    }

    public function chat() {
        return $this->hasOne('App\Models\Chat');
    }

    public function getNoviosAttribute() {
        return User::where('rol', 'user')->where('boda_id', $this->id)->first();
    }

    public function contratoValues($doc_id) {
        
    }

    public function cobros() {
        return $this->hasMany('App\Models\Cobro', 'boda_id');
    }

    public function facturas() {
        return $this->hasMany('App\Models\Factura', 'boda_id');
    }

    public function getProgresoAttribute() {
        return percentageBetweenDates($this->created_at, $this->date.' 00:00:00');
    }

    public function getIngresoAttribute() {
        $path = 'ingreso/'.$this->id.'.jpg';
        if(\Storage::disk('public')->exists('ingreso/'.$this->id.'.jpg')) {
            return '/storage/'.$path;
        } else {
            return null;
        }
    }

    public function getJustificanteAttribute() {
        // $path = 'app/public/ingreso/'.$this->token;
        // $files = File::files(storage_path($path));
        // $file = collect($files)->first();
        // $file = basename($file);
        // if($file == '') {
        //     return null;
        // }
        // return '/storage/ingreso/'.$this->token.'/'.$file;

        return null;
    }
    

    /*** CUSTOM ATTRIBUTES ***/
    public function getRefAttribute() {
        $place_cod = $this->place->value ?? ' ';
        $year = substr($this->year, 2, 4);
        $string = 'BOD '.$place_cod.' '.$year.' '.$this->name;
        return $string;
    }

    public function getDatosAttribute() {
        return \DB::table('bodas_datos')->where('token', $this->token)->first();
    }

    public function getUserComsAttribute() {
        return User::where('boda_id', $this->id)->where('rol', 'user')->first();
    }

    public function getConceptoAttribute() {
        return 'BODA '.$this->codigo.' '.Carbon::parse($this->date)->format('d/m/Y').' '.$this->place->value;
    }
    
}
