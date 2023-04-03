<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use DateTime;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Models\Place;
use App\Models\Boda;
use App\Models\Doc;
use App\Models\Email;
use App\Models\DocSigned;
use App\Models\Mesa;
use App\Models\Plano;
use App\Models\GrupoOferta;
use App\Models\Invitado;
use App\Models\Categorias_oferta_gastronomica;
use App\Models\Productos_oferta_gastronomica;
use App\Models\Catering_place;
use App\Models\Selecciones_comercial_oferta_gastronomica;
use App\Models\Selecciones_usuario_oferta_gastronomica;
use App\Models\ContratoValue;
use App\Models\ContratoField;
use App\Models\Cobro;
use Illuminate\Support\Str;
use App\Models\Activity;

use App\Mail\CompleteData;
use App\Mail\SignDoc;
use App\Mail\Notif;
use App\Mail\CompleteOfertaGastronomica;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    public function resumen() {
        if(user()->rol == 'user') {
            return redirect(route('user.boda'));
        }

        //return view('admin.resumen');
        return redirect(route('admin.bodas'));
    }

    public function bodas(Request $request) {
        if(user()->rol == 'com') { return redirect(route('com.bodas')); }
        if(isset($request->com)) {
            $bodas = User::find($request->com)->bodas;
        } else {
            $bodas = Boda::all();
        }
        return view('admin.bodas')->with(compact('bodas'));
    }

    public function bodas_crear() {
        return view('admin.bodas.crear');
    }

    public function bodas_ver($id) {
        $boda = Boda::find($id);
        if($boda == null) { return redirect(route('admin.bodas'))->withError('No existe boda con el ID: '.$id); }
        $facturas = $boda->cobros;
        $datos = $boda->datos;
        $docs = [];
        $docs_firmar = [];
        $docs_visual = [];
        $docs_otros = [];

        if(isset($boda->place->docs)) {
            $docs = $boda->place->docs ?? [];
            $docs_firmar = $docs->where('category_id', 1);
            $docs_visual = $docs->whereNotIn('category_id', [1, 5]);
            $docs_otros = $boda->all_docs;
        }
        
        $categorias_productos = Categorias_oferta_gastronomica::all();
        //$prod_oferta_gastron = Productos_oferta_gastronomica::where('visible', 1)->get();

        $selecciones = $boda->selecciones_comercial_oferta_gastronomica->groupBy('type');
        $selecciones_pre = $selecciones['pre'] ?? null;
        $selecciones_final = $selecciones['final'] ?? null;
        $comerciales = User::where('rol', 'com')->get();
        $user_coms = $boda->user_coms;
        
        return view('admin.bodas.ver')->with(compact('boda', 'selecciones_pre', 'selecciones_final', 'datos', 'docs', 'user_coms', 'docs_firmar', 'docs_visual', 'docs_otros',
        'categorias_productos', 'comerciales', 'facturas'));
    }

    public function cambio_estado(Request $request){
        $cobro = Cobro::find($request->cobro_id);
        $cobro->status = $request->estado;
        $cobro->save();
    }

    public function bodas_crear_enviar(Request $request) {
        $fecha = Carbon::parse($request->date);
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        /*if ($request->place_id == 3 && !isset($request->localizacion_catering)) {
            return redirect()->back()->withError('Si has seleccionado catering, tienes que poner una localización. Por favor, rellena el campo de localización.');
        }*/
        // return $request->all();
        $boda = new Boda();
        $boda->fill($request->all());
        $boda->date = $fecha->format('Y-m-d');
        $boda->year = $fecha->format('Y');
        $boda->token = $token;
        $boda->email_datos = \Carbon\Carbon::now();
        $boda->place_id = $request->place_id;
        $boda->save();

         // Crear usuario
        $passwd = generatePassword();
        $user = User::create([
            'username' => $request->email,
            'email' => $request->email,
            'password' => Hash::make($passwd),
            'rol' => 'user',
            'boda_id' => $boda->id
        ]);

        if($request->has('com_id'))  {
            foreach($request->com_id as $com) {
                $com = DB::table('bodas_coms')->insert(['boda_id' => $boda->id, 'com_id' => $com]);
            }
        }

        if (!isset($request->reference)) {
            $boda->update(['reference' => $boda->ref]);
        }

        if ($boda->place_id == 3) {
            Catering_place::create([
                'boda_id' => $boda->id, 
                'valor' => $request->localizacion_catering,
                'name' => $request->name_localizacion_catering
            ]);
        }

        addActivity('Boda creada', $boda->id, user()->id);
        addActivity('Email para completar datos enviado', $boda->id, user()->id);

        // Chat con comercial
        $chat = new Chat();
        $chat->boda_id = $boda->id;
        $chat->save();

        // Email de confirmacion
        $data = getMail('confirmacion');

        $mail_string = '<div class="lh">¡Hola '.$boda->name.'!<br>Ante todo, muchas gracias por confiar en nosotros para un momento tan especial.<br>';
        $mail_string .= '<br>Éstos son los datos para acceder a la aplicación:<br>';
        $mail_string .= 'Usuario: <strong>'.$boda->email.'</strong><br>';
        $mail_string .= 'Contraseña: <strong>'.$passwd.'</strong><br>';
        $mail_string .= '<br>A continuación, detallamos la información necesaria para realizar la reserva:<br>';
        $mail_string .= '<br>TITULAR: CAMPOS DE CÓRDOBA S.A.<br>';
        $mail_string .= 'BANCO: BANKINTER<br>';
        $mail_string .= 'IBAN: ES1001289471860100011282<br>';
        $mail_string .= 'SWIFT: BKBKESMMXXX<br>';
        $mail_string .= 'CONCEPTO: ' . $boda->concepto;
        $mail_string .= 'CANTIDAD: 2.000€<br><br>';
        $mail_string .= 'Es necesario que nos facilitéis vuestros datos personales a través del siguiente enlace:</div>';

        $mail = [
            'name' => $boda->name,
            'title' => $data->title,
            // 'msg' => $data->msg,
            'msg' => $mail_string,
            'btn' => $data->btn,
            'url' => route($data->url, ['token' => $boda->token])
        ];

        Mail::to($boda->email)->send(new Notif($mail));
        
        return redirect(route('admin.bodas'))->withSuccess('La boda ha sido registrada correctamente.');
    }

    public function bodas_editar(Request $request) {
        // return $request->all();
        $fecha = Carbon::parse($request->date);

        $boda = Boda::find($request->id);
        $boda->fill($request->all());
        $boda->date = $fecha->format('Y-m-d');
        $boda->year = $fecha->format('Y');
        $boda->save();

        // Modificar correo para iniciar sesión
        $user = $boda->novios;
        if($user != null){
            $user->username = $request->email;
            $user->email = $request->email;
            $user->save();
        }
        
        

        if ($boda->place_id == 3) {
            // Buscar catering
            if($boda->catering) {
                $catering = Catering_place::find($boda->catering->id);
                $catering->valor = $request->localizacion_catering ?? $catering->valor;
                $catering->name = $request->name_localizacion_catering;
                $catering->save();
            } else {
                if(!isset($request->localizacion_catering)) { return redirect()->back()->withError('Si has seleccionado catering, tienes que indicar una localización. Por favor, rellena el campo de localización.'); }
                Catering_place::create([
                    'boda_id' => $boda->id,
                    'valor' => $request->localizacion_catering,
                    'name' => $request->name_localizacion_catering
                ]);
            }
        }

        // Modificar comerciales
        if(isset($request->com_id)):
            DB::table('bodas_coms')->where('boda_id', $boda->id)->delete();
            foreach($request->com_id as $com) {
                DB::table('bodas_coms')->insert(['boda_id' => $boda->id, 'com_id' => $com]);
            }
        endif;
        
        addActivity('Detalles de la boda modificados', $request->id, user()->id);

        return redirect()->back()->withSuccess('Los datos han sido guardados correctamente.');
    }

    public function bodas_editarpersonaldata(Request $request) {
        // return $request->all();
        // return bcrypt($request->passwd);
        $boda = Boda::find($request->id);
        $user = User::find($request->user_id);

        DB::table('bodas_datos')->where('token', $boda->token)->update($request->except('_token', 'user_id', 'id', 'email_comms', 'user', 'passwd', 'rep_passwd'));

        if ((isset($request->passwd))) {
            if (isset($request->rep_passwd)) {
                if ($request->passwd != $request->rep_passwd) {
                    return redirect()->back()->withError('Las contraseñas no coinciden. Por favor, completa tus datos correctamente.');
                }
            }
    
            if (!preg_match ('/^[0-9]{8}[a-z]?$/i', $request->dni_1) || !preg_match ('/^[0-9]{8}[a-z]?$/i', $request->dni_2)) {
                return redirect()->back()->withError('El dni no tiene el formato correcto. Ponlo en su formato. Ejemplo: 00000000Z');
            }

            // Actualizar usuario
            $user->update([
                'email' => $request->email_comms,
                'username' => $request->user,
                'name' => $request->user,
                'password' => bcrypt($request->passwd)
            ]);

        } else {
            $user->update([
                'email' => $request->email_comms,
                'username' => $request->user
            ]);
        }
        
        
        return redirect()->back()->withSuccess('Los datos han sido guardados correctamente.');
    }

    public function notificacion_completar(Request $request) {
        $boda = Boda::find($request->id);

        // $mail_string = '<div class="lh">¡Hola '.$boda->name.'!<br>Ante todo, muchas gracias por confiar en nosotros para un momento tan especial.<br>';
        // $mail_string .= 'A continuación, detallamos la información necesaria para realizar la reserva:<br>';
        // $mail_string .= '<br>TITULAR: CAMPOS DE CÓRDOBA S.A.<br>';
        // $mail_string .= 'BANCO: BANKINTER<br>';
        // $mail_string .= 'IBAN: ES1001289471860100011282<br>';
        // $mail_string .= 'SWIFT: BKBKESMMXXX<br>';
        // $mail_string .= 'CONCEPTO: BODA …… & …… 00/00/2022 TDB<br>';
        // $mail_string .= 'CANTIDAD: 2.000€<br><br>';
        // $mail_string .= 'Es necesario que nos faciliteis vuestros datos personales a través del siguiente enlace:</div>';

        $data = getMail('confirmacion');

        $mail = [
            'name' => $boda->name,
            'title' => $data->title,
            'msg' => $data->msg,
            'btn' => $data->btn,
            'url' => route($data->url, ['token' => $boda->token])
        ];

        Mail::to($boda->email)->send(new Notif($mail));

        $boda->email_datos = \Carbon\Carbon::now();
        $boda->save();

        addActivity('Email para completar datos enviado', $boda->id, user()->id);

        return redirect()->back()->withSuccess('Enviado correctamente.');
    }

    public function bodas_completar($token = null) {
        $boda = Boda::where('token', $token)->first();
        if($boda == null)
            abort(404);
        return view('front.completar')->with(compact('token', 'boda'));
    }

    public function bodas_completar_enviar(Request $request) {
        
        // Validar repetir contraseña
        if ($request->passwd != $request->rep_passwd)
            return redirect()->back()->withInput()->withError('Las contraseñas no coinciden. Por favor, completa tus datos correctamente.');

        // Validar DNI
        if ($request->nacionalidad_1 == 'ES' && validateDNI($request->dni_1) == false)
            return redirect()->back()->withInput()->withError('El campo DNI 1 no contiene el formato correcto. Ejemplo: 00000000Z');

        if ($request->nacionalidad_2 == 'ES' && validateDNI($request->dni_2) == false)
            return redirect()->back()->withInput()->withError('El campo DNI 2 no contiene el formato correcto. Ejemplo: 00000000Z');

        // Validar usuario
        // if(User::where('username', $request->username)->count() > 0)
        //     return redirect()->back()->withInput()->withError('Ha surgido un problema, vuelva a intentarlo o contacte con el administrador.');

        // Insertar DATOS PERSONALES en tabla y exceptuar campos
        DB::table('bodas_datos')->insert([
            'nombre_1' => $request->nombre_1,
            'telefono_1' => $request->telefono_1,
            'apellidos_1' => $request->apellido1_1.' '.$request->apellido1_2,
            'direccion_1' => $request->direccion_1,
            'cp_1' => $request->cp_1,
            'email_1' => $request->email_1,
            'dni_1' => $request->dni_1,
            'nacionalidad_1' => $request->nacionalidad_1,
            /////////////
            'nombre_2' => $request->nombre_2,
            'telefono_2' => $request->telefono_2,
            'apellidos_2' => $request->apellido2_1.' '.$request->apellido2_2,
            'direccion_2' => $request->direccion_2,
            'cp_2' => $request->cp_2,
            'email_2' => $request->email_2,
            'dni_2' => $request->dni_2,
            'nacionalidad_2' => $request->nacionalidad_2,
            /////////////
            'comentarios' => $request->comentarios,
            'token' => $request->token,
        ]);

        $boda = Boda::where('token', $request->token)->first();
        $boda->email = $request->email_comms;
        $boda->save();

        // // Crear usuario
        // $user = User::create([
        //     'username' => $request->username,
        //     'email' => $request->email_comms,
        //     'password' => Hash::make($request->passwd),
        //     'rol' => 'user',
        //     'boda_id' => $boda->id
        // ]);

        // Mensaje de bienvenida
        /*$msg = new Message();
        $msg->message = '<p>¡Hola!</p><p>Desde aquí podremos estar en contacto en todo momento para cualquier sugerencia o duda. También os haremos saber si disponéis de nuevos documentos para ser firmados.</p><p>¡Gracias por contar con nosotros!</p>';
        $msg->chat_id = $chat->id;
        $msg->user_id = $boda->coms[0]->id;
        $msg->readed = 0;
        $msg->save();*/

        // Auto-login
        $auth = auth()->login($boda->novios, true); 

        // Email para comercial
        $mail_string = 'Los datos para la boda <b>'.$boda->ref.'</b> han sido registrados correctamente. Ya puedes consultarlos desde el panel y enviar documentos.';
        $mail = [
            'name' => $boda->name,
            'title' => 'Datos de boda registrados',
            'msg' => $mail_string,
            'btn' => 'Acceder al panel',
            'url' => route('admin')
        ];

        addActivity('Novios han completado datos personales', $boda->id, user()->id);

        foreach($boda->coms as $com) {
            Mail::to($com->email)->send(new Notif($mail));
        }

        // Email para usuario
        $mail_string2 = 'Ya puedes ingresar en nuestra aplicación:<br><br>E-mail: '.$request->email_comms.'<br>Clave: '.$request->passwd.'<br><br>';
        $mail_string2 .= 'Descargar para <b>Android</b>: <a href="https://play.google.com/store/apps/details?id=com.taller.bodas">https://play.google.com/store/apps/details?id=com.taller.bodas</a><br>';
        $mail_string2 .= 'Descargar para <b>iPhone</b>: <a href="https://apps.apple.com/es/app/de-boda-en-bodegas/id6443830259">https://apps.apple.com/es/app/de-boda-en-bodegas/id6443830259</a>';
        $mail2 = [
            'name' => $boda->name,
            'title' => 'Acceso a la aplicación',
            'msg' => $mail_string2,
            'btn' => 'Acceder a web',
            'url' => route('user.boda')
        ];

        Mail::to($request->email_comms)->send(new Notif($mail2));
        
        return redirect('/')->withSuccess('Los datos han sido enviados correctamente y ya puedes acceder al panel de tu boda.');
    }

    public function bodas_doc_borrador($id, $doc_id) {
        $boda = Boda::find($id);
        $doc = Doc::find($doc_id);
        $fields = $doc->fields;
        $datos = $boda->datos;
        $adicional = [];
        foreach($fields as $field) {
            $adicional[$field->name] = $field->value($id);
        }

        $adicional = json_encode($adicional);

        if($boda == null OR $doc == null)
            return redirect()->back()->withError('La boda o el documento no existe');

        return view('admin.bodas.doc')->with(compact('boda', 'doc', 'datos', 'adicional'));
    }

    public function bodas_doc_datos(Request $request) {
        $doc = Doc::find($request->doc);
        $boda = Boda::find($request->id);

        $decoracion_show = ContratoField::where('doc_id', $doc->id)->where('name', 'decoracion')->get();
        if(count($decoracion_show) == 1){
            $decoracion_show = true;
        }else{
            $decoracion_show = false;
        }

        return view('admin.documentos.values')->with(compact('doc', 'boda', 'decoracion_show'));
    }

    public function delete_imagen_decoration($boda_id){
        try{
            $files = File::files(storage_path('app/public/contratos/'.$boda_id.'/'));
            if(count($files) > 0){
                foreach($files as $file){
                    if(file_exists($file)){
                        $ext = explode('.', $file->getFilename());
                        if(count($ext) > 1){
                            if($ext[1] == 'jpg' || $ext[1] == 'png'){
                                $value = Str::contains($file->getFilename(), '_imagen');
                                if($value == true){
                                    File::delete($file);
                                }
                            }
                        }
                    }   
                }
            }  
        }catch(\Exception $e){

        }
    }

    public function bodas_doc_datos_enviar(Request $request) {
        $file = $request->file('files');
        $doc = Doc::find($request->doc_id);
        if(isset($file)){
            $path = public_path().'/storage/contratos/'. $request->boda_id . '/'.$request->doc_id.'.docx';
            if(isset($path)){
                if($file != '' && $file != null) {
                    $ext = $file->getClientOriginalExtension();
                    if($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg') {
                        return redirect()->back()->withError('El archivo debe tener el formato correcto: JPG o PNG');
                    }

                    $this->delete_imagen_decoration($request->boda_id);

                    //Almacenamiento físico
                    Storage::disk('public')->putFileAs(
                        'contratos/'.$request->boda_id.'/',
                        $file,
                        $request->doc_id.'_imagen.'.$ext
                    ); 
          
                    
                    $template = new \PhpOffice\PhpWord\TemplateProcessor(substr($doc->public_url, 1));
                    $path = 'storage/contratos/'.$request->boda_id.'/';
                    if(!file_exists($path)){ mkdir($path, 0755, true); } // Crear directorio si no existe
                    $template->setImageValue('_decoracion', public_path($path.$request->doc_id.'_imagen.'.$ext));
                    $template->saveAs(public_path($path.$doc->id.'.docx'));

                    // GUARDAR COPIA EN PDF
                    DocToPDF(public_path($path.$doc->id.'.docx'), public_path($path));
                    
                    
                }
            }
        }
            
        foreach($request->values as $key => $value) {
            $check_value = ContratoValue::where('doc_id', $request->doc_id)->where('boda_id', $request->boda_id)->where('field_id', $key)->first();
            $datos = ['value' => $value, 'field_id' => $key, 'doc_id' => $request->doc_id, 'boda_id' => $request->boda_id];
            if($check_value == null) {
                ContratoValue::create($datos);
            } else {
                $check_value->update($datos);
            }
            
            // Añadir clausulas adicionales
            $adicional = ContratoValue::where(['doc_id' => $request->doc_id, 'boda_id' => $request->boda_id, 'field_id' => 0])->first();
            if($adicional == null) {
                ContratoValue::create(['doc_id' => $request->doc_id, 'boda_id' => $request->boda_id, 'field_id' => 0, 'value' => $request->clausulas_adicionales]);
            } else {
                $adicional->update(['value' => $request->clausulas_adicionales]);
            }
        }

        return redirect()->route('admin.bodas.ver', ['id' => $request->boda_id])->withSuccess('Los datos requeridos han sido guardados correctamente.');
    }

    public function bodas_doc_enviar(Request $request) {
        $doc = Doc::find($request->doc_id);
        if($doc == null)
            return redirect()->back()->withErrors('No se encuentra el documento');

        $boda = Boda::find($request->boda_id);
        if($boda == null)
            return redirect()->back()->withError('No se encuentra boda con ID: '.$request->boda_id);

        // Guardar documento en local
        $base64_pdf = $request->pdfBase64;
        $data = substr($base64_pdf, strpos($base64_pdf, ',') + 1);
        $data = base64_decode($data);
        Storage::disk('public')->put('signs/'.$request->boda_id.'/'.$request->doc_id.'.pdf', $data);

        // Guardar documento en bbdd
        $doc_signed =  new DocSigned();
        $doc_signed->token = \Str::random(12);
        $doc_signed->boda_id = $request->boda_id;
        $doc_signed->doc_id = $request->doc_id;
        $doc_signed->updated_at = null;
        
        $doc_signed->save();

        // Enviar mail a cliente
        $mail = getMail('doc_firmar');
        $data = [
            'name' => $boda->name,
            'title' => $mail->title,
            'msg' => $mail->msg,
            'btn' => $mail->btn,
            'url' => route($mail->url),
        ];

        addActivity('Contrato enviado para firmar', $boda->id, user()->id);
    
        Mail::to($boda->email)->send(new Notif($data));

        return redirect(route('admin.bodas.ver', ['id' => $request->boda_id]))->withSuccess('El documento ha sido enviado correctamente');
    }

    public function bodas_doc_enviar_otros(Request $request) {
        $boda = Boda::find($request->boda_id);
        if($boda == null)
            return redirect()->back()->withError('No se encuentra boda con ID: '.$request->boda_id);

        $doc = DocSigned::where('boda_id', $boda->id)->where('doc_id', $request->doc_id)->first();

        if($doc != null) {
            $doc->updated_at = Carbon::now();
            $doc->save();
        } else {
            // Guardar documento en bbdd
            $doc =  new DocSigned();
            $doc->token = \Str::random(12);
            $doc->boda_id = $request->boda_id;
            $doc->doc_id = $request->doc_id;
            $doc->updated_at = null;
            $doc->firmado = 1;
            $doc->save();
        }

        $mail = getMail('doc_visualizar');

        $data = [
            'name' => $boda->name,
            'title' => $mail->title,
            'msg' => $mail->msg,
            'btn' => $mail->btn,
            'url' => route($mail->url),
        ];

        Mail::to($boda->email)->send(new Notif($data));

        addActivity('Documento enviado', $boda->id, user()->id);

        return redirect(route('admin.bodas.ver', ['id' => $request->boda_id]))->withSuccess('El documento ha sido enviado correctamente');
    }

    public function mensajes() {
        $documento = null;
        $id = auth()->user()->id;
        $chats = auth()->user()->chats->orderBy('updated_at', 'desc')->get();
        
        return view('admin.mensajes')->with(compact('chats'));
    }

    public function mensajes_chat($id) {
        $documento_subida = null;
        $ver_tipo_user = null;
        $files = null;
        $url_file_subida = null;
        $ext = null;
        $exts = null;
        $arr_ext = null;
        $arr_files = null;

        $chat = Chat::find($id);
        Message::where('chat_id', $id)->where('user_id', '!=', user()->id)->update(['readed' => 1]);
        $messages = Message::where('chat_id', $id)->orderBy('created_at', 'asc')->get(); 
        $ver_tipo_user = 'upload/';

        try{
            $files_com = File::files(storage_path('app/public/'.$ver_tipo_user));
            if(count($files_com) > 0){
                foreach($files_com as $file){
                    if(file_exists($file)){
                        $ver = explode('_', basename($file));
                        if(count($ver) > 8){
                            $ver_chat_id = $ver[6];
                            $ver_user_id = $ver[7];
                            if((int)$ver_chat_id == $id){
                                if((int)$ver_user_id == user()->id){
                                    $ver_ext = explode('.', end($ver));
                                    if(count($ver_ext) > 0){
                                        if(count($ver_ext) == 1){
                                            $url_file_subida = basename($file);
                                            $ext = explode('.', basename($file));
                                            $exts = $ext[1];
                                        }else{
                                            $url_file_subida.= basename($file).',';
                                            $ext = explode('.', basename($file));
                                            $exts.= $ext[1].',';
                                        }
                                    }
                                }
                            }
                        }
                    }   
                }

                if(isset($url_file_subida)){
                    $arr_files = explode(',', $url_file_subida);
                }
    
                if(isset($exts)){
                    $arr_ext = explode(',', $exts);
                }
            }

        }catch(\Exception $e){

        }


        return view('admin.mensajes.chat')->with(compact('messages', 'chat', 'documento_subida', 'url_file_subida', 'arr_files', 'arr_ext'));
    }

    public function download(Request $request){

        $headers = array(
            'Content-Type:' => 'application/pdf, text/plain, image/jpg, image/png' 
        );

        $file_path = storage_path('app/public/documentos/'.$request->file_name);
        
        return response()->download($file_path, $request->file_name, $headers);
    }

    public function mensajes_enviar(Request $request) {
        $url_file_subida = '';
        $ver_tipo_user = 'upload/';
        $ext = null;
        $exts = null;
        $arr_ext = null;
        $arr_files = null;
        
        try{
            $files_com  = File::files(storage_path('app/public/'.$ver_tipo_user));
            if(count($files_com) > 0){
                foreach($files_com as $file){
                    if(file_exists($file)){
                        $ver = explode('_', basename($file));
                        if(count($ver) > 8){
                            $ver_chat_id = $ver[6];
                            $ver_user_id = $ver[7];
                            if((int)$ver_chat_id == $request->chat_id){
                                if((int)$ver_user_id == user()->id){
                                    $ver_ext = explode('.', end($ver));
                                    if(count($ver_ext) > 0){
                                        if(count($ver_ext) == 1){
                                            $url_file_subida = basename($file);
                                        }else{
                                            $url_file_subida.= basename($file).',';
                                        }
                                    }
                                }
                            }
                        }
                    }   
                }
            }
        }catch(\Exception $e){

        }

        $validator = Message::validate(array(
            'message' =>$request->message,
         ));

         if($validator->fails()){
            return redirect()->back()->withErrors(['msg' => 'Es necesario completar el campo de respuesta para poder enviar.']);
         }else{
            $msg = new Message();

            if(isset($url_file_subida)){
                $msg->attachment = $url_file_subida; 
            }
            $msg->message = $request->message;
            $msg->chat_id = $request->chat_id;
            $msg->user_id = auth()->user()->id;
            $msg->readed = 0;
            $msg->save();
         }
        
        

        try{
            $files = File::files(storage_path('app/public/upload/'));
            if(count($files) > 0){
                foreach($files as $file){
                    if(file_exists($file)){
                        File::delete($file);
                    }   
                }
            }  
        }catch(\Exception $e){

        }

        return redirect()->back();
    }

    public function mensajes_borrar(Request $request) {
        $message = Message::find($request->id);

        // Comprobar si tiene permisos para borrar
        if($message->from_id != auth()->user()->id && !in_array(auth()->user()->rol, ['admin', 'com']))
            return redirect()->back();

        $message->delete();

        return redirect()->back()->withSuccess('El mensaje ha sido eliminado correctamente.');
    }

    // DOCUMENTOS
    public function documento(Request $request){
        $uploadedFile = null;
        $chat_id = $request->chat_id;
        $user_id = $request->user_id;
        $uploadedFile = $request->file('files');

        if($uploadedFile != null){
            $name = $uploadedFile->getClientOriginalName();
            $name_original = str_replace(' ', '_', $name);
               
            $time = new DateTime();
            $filename = $time->format('d_m_Y H_i_s')."_".$chat_id.'_'.$user_id.'_'.$name_original;  
            $filename = str_replace(' ', '_', $filename);    

            Storage::disk('public')->putFileAs(
                'documentos/',
                $uploadedFile,
                $filename
            );  
            
            Storage::disk('public')->putFileAs(
                'upload/',
                $uploadedFile,
                $filename
            );   

            //return redirect()->back()->withSuccess('Documento subido correctamente.');
        }
        
        return response()->json($filename);
        //return redirect()->back();
    }

    public function elimina_documento(Request $request){
        try{
            $files = File::files(storage_path('app/public/upload/'));
            if(count($files) > 0){
                foreach($files as $file){
                    if(file_exists($file)){
                        if(basename($file) == $request->file_name){ 
                            File::delete($file); 
                            return response()->json($request->file_name);
                        }
                    }   
                }
            }  
        }catch(\Exception $e){

        }

        //return redirect()->back();     
    }

    public function documentos() {
        $docs = Doc::where('type', 'global')->get();
        return view('admin.documentos')->with(compact('docs'));
    }

    public function documentos_subir(Request $request) {
        
        $name = $request->name;
        $uploadedFile = $request->file('files');
        //return $uploadedFile;
        $ext = $uploadedFile->getClientOriginalExtension();
        
        if($ext != 'docx' && $ext != 'pdf') // Check ext
            return redirect()->back()->withError('El formato del archivo debe ser .pdf/.docx');

        if($request->type == 'private') {
            $boda_id = $request->boda_id;
        } else {
            $boda_id = null;
        }
        
        $doc = new Doc();
        $doc->name = $name;
        $doc->type = $request->type;
        $doc->boda_id = $boda_id;
        $doc->category_id = $request->category_id;

        $doc->save();

        foreach($request->place_ids as $place_id) {
            DB::table('docs_places')->insert(['doc_id' => $doc->id, 'place_id' => $place_id]);
        }

        $filename = $doc->id.'.'.$ext;

        $path = ($request->category_id == 1) ? '/contratos' : '/docs';

        $file_url = Storage::disk('public')->putFileAs(
            $path,
            $uploadedFile,
            $filename
        );

        if($ext == 'docx') {
           DocToPDF(public_path('storage/contratos/'.$doc->id.'.docx'), public_path('storage/contratos/'));

        }

        if($request->category_id == 1) {
            return redirect(route('admin.contratos.editar', ['id' => $doc->id]));
        } 

        return redirect()->back()->withSuccess('El documento ha sido subido correctamente.');
    }

    public function documentos_editar(Request $request) {
        $doc = Doc::find($request->id);
        if($doc == null)
            return redirect()->back()->withError('No es posible encontrar ese documento.');

        // Limpiar lineas
        DB::table('docs_places')->where('doc_id', $doc->id)->delete();

        // Actualizar datos
        $doc->name = $request->name;
        $doc->category_id = $request->category_id;

        foreach($request->place_ids as $place_id){
            DB::table('docs_places')->insert(['doc_id' => $doc->id, 'place_id' => $place_id]);
        }

        $doc->save();

        return redirect()->back()->withSuccess('Documento editado correctamente.');
    }

    public function documentos_campos($id) {
        $doc = Doc::find($id);
        return view('admin.documentos.fields')->with(compact('doc'));
    }

    public function documentos_campos_enviar(Request $request) {
        return $request->all();
    }

    public function documentos_eliminar($id) {
        $file = Doc::find($id);
        Storage::delete($file->storage_url);
        $file->delete();
        return redirect()->back()->withSuccess('El documento ha sido eliminado correctamente.');
    }

    public function documentos_descargar($id) {
        $file = Doc::find($id);
        return Storage::download($file->storage_url, $file->name.'.pdf');
    }

    public function documentos_ver() {
        return view('admin.documentos.ver');
    }

    public function documentos_firmar($token) {
        $doc_sign = DocSigned::where('token', $token)->first();
        $boda = $doc_sign->boda;
        if($doc_sign == null)
            return redirect('/')->withError('No se encuentra el documento');

        return view('admin.documentos.firmar')->with(compact('doc_sign', 'boda'));
    }

    public function documentos_firmar_enviar(Request $request) {
        $doc_sign = DocSigned::find($request->doc_sign);
        $base64 = $request->base64;
        if($base64 == null OR $base64 == '')
            return redirect(route('admin.documentos.firmar', ['token' => $doc_sign->token]))->with(['error' => 'Es necesario firmar el documento.']);
        return view('admin.documentos.sign')->with(compact('doc_sign', 'base64'));
    }

    public function documentos_firmar_sign(Request $request) {
        $doc_sign = DocSigned::find($request->doc_sign);

        // Guardar documento en local
        $base64_pdf = $request->pdfbytes;
        $data = substr($base64_pdf, strpos($base64_pdf, ',') + 1);
        $data = base64_decode($data);

        Storage::disk('public')->put('signs/'.$doc_sign->boda_id.'/'.$doc_sign->doc_id.'_signed.pdf', $data);

        $doc_sign->firmado = 1;
        $doc_sign->save();

        $mail_string = 'El documento <b>'.$doc_sign->doc->name.'</b> que fue enviado a <b>'.$doc_sign->boda->name.'</b> ha sido firmado. Accede al panel desde el siguiente enlace:';

        $mail = [
            'name' => $doc_sign->boda->name,
            'title' => 'Un documento ha sido firmado',
            'msg' => $mail_string,
            'btn' => 'Acceder al panel',
            'url' => route('admin')
        ];

        $boda = Boda::find($doc_sign->boda_id);
        foreach($boda->coms as $com) {
            Mail::to($com->email)->send(new Notif($mail));
        }

        return redirect(route('user.documentos'))->withSuccess('Documento firmado y guardado correctamente. El comercial será notificado.');
    }

    public function comerciales() {
        $coms = User::where('rol', 'com')->get();
        return view('admin.comerciales')->with(compact('coms'));
    }

    public function comerciales_crear() {
        return view('admin.comerciales.crear');
    }

    public function comerciales_crear_enviar(Request $request) {
        $com = new User();
        $com->username = $request->username;
        $com->name = $request->name;
        $com->email = $request->email;
        $com->password = Hash::make($request->password);
        $com->rol = 'com';
        $com->boda_id = null;
        $com->save();
        return redirect(route('admin.comerciales'))->withSuccess('Comercial creado correctamente');
    }

    public function comerciales_editar($id) {
        $user = User::find($id);

        if($user == null)
            return back()->withError('No existe ese usuario');

        if($user->rol != 'com')
            return back()->withError('No tienes permiso para editar ese usuario');

        return view('admin.comerciales.editar')->with(compact('user'));
    }

    public function comerciales_editar_enviar(Request $request) {
        $com = User::find($request->id);
        $com->username = $request->username;
        $com->name = $request->name;
        $com->email = $request->email;
        if($request->has('password')){
            $com->password = Hash::make($request->password);
        }
        $com->boda_id = null;
        $com->save();
        return redirect(route('admin.comerciales'))->withSuccess('Comercial editado correctamente');
    }

    public function calendario() {
        return view('admin.calendario');
    }

    public function notas() {
        return view('admin.notas');
    }

    public function add_grupo_oferta() {
        $categorias = Categorias_oferta_gastronomica::all();
        return view('admin.oferta_gastronomica.add_grupo')->with(compact('categorias'));
    }

    public function crear_grupo_oferta(Request $request) {
        $value = serialize($request->grupo);
        $grupo = new GrupoOferta();
        $grupo->name = $request->nombre;
        $grupo->value = $value;
        $grupo->save();
        return redirect(route('admin.oferta_gastronomica'))->withSuccess('El grupo se ha creado correctamente');
    }

    public function editar_grupo_oferta($id) {
        $grupo = GrupoOferta::find($id);
        $categorias = Categorias_oferta_gastronomica::all();
        // return unserialize($grupo->value);
        return view('admin.oferta_gastronomica.add_grupo')->with(compact('grupo', 'categorias', 'id'));
    }

    public function modificar_grupo_oferta(Request $request) {
        $value = serialize($request->grupo);
        $grupo = GrupoOferta::find($request->id);
        $grupo->name = $request->nombre;
        $grupo->value = $value;
        $grupo->update();
        return redirect(route('admin.oferta_gastronomica'))->withSuccess('El grupo se ha actualizado correctamente');
    }

    public function oferta_gastronomica(Request $request) {
        $productos = Productos_oferta_gastronomica::all();
        $grupos = GrupoOferta::all();
        return view('admin.oferta_gastronomica.list', compact('productos', 'grupos'));
    }

    public function add_oferta_gastronomica_view(Request $request) {
        $categorias = Categorias_oferta_gastronomica::all();
        return view('admin.oferta_gastronomica.add', compact('categorias'));
    }

    public function add_oferta_gastronomica(Request $request) {
        //Productos_oferta_gastronomica::create($request->all());
        $producto = new Productos_oferta_gastronomica();
        $producto->nombre = $request->nombre;
        $producto->visible = 1;
        $producto->save();

        foreach($request->categorias as $cat) {
            DB::table('categorias_productos')->insert(['id_producto' => $producto->id, 'id_categoria' => $cat]);
        }

        return redirect(route('admin.oferta_gastronomica'))->withSuccess('El producto se ha creado correctamente');
    }


    public function OG_seleccion_imprimir(Request $request) {
        $boda = Boda::find($request->id);
        $seleccion = Selecciones_usuario_oferta_gastronomica::find($request->id_seleccion);
        $seleccion_com = $seleccion->selecciones_comercial;
        $selecciones = call_user_func_array('array_merge', unserialize($seleccion->selecciones));
        $selecciones_com = call_user_func_array('array_merge', unserialize($seleccion_com->selecciones));
        $categorias = Categorias_oferta_gastronomica::all();
        
        $data = compact('boda', 'selecciones', 'seleccion', 'seleccion_com', 'selecciones_com', 'categorias');
        $pdf = Pdf::loadView('admin.oferta_gastronomica.imprimir', $data);
        return $pdf->stream($boda->name.'_OFERTA-GASTRO.pdf');
        
        //return view('admin.bodas.ver_seleccion_usuario', compact('boda', 'seleccion', 'categorias', 'selecciones'));
        
        /*
        if($sel != null) { $seleccion = $sel->values; } else { return redirect()->route('root')->withError('No existe la selección'); }
        $selecciones = Selecciones_comercial_oferta_gastronomica::find($sel->id_seleccion_com);
        $boda = Boda::find($request->id);

        $data = compact('boda', 'selecciones', 'seleccion');
        //$pdf = Pdf::loadView('admin.oferta_gastronomica.imprimir', $data);
        return view('admin.oferta_gastronomica.imprimir')->with($data);
        //return $pdf->stream($boda->name.'_OFERTA-GASTRO.pdf');*/
    }

    public function edit_oferta_gastronomica_view(Request $request) {
        $categorias = Categorias_oferta_gastronomica::all();
        $producto = Productos_oferta_gastronomica::find($request->id);        

        return view('admin.oferta_gastronomica.add', compact('categorias', 'producto'));
    }

    public function edit_oferta_gastronomica(Request $request) {
        //$producto = Productos_oferta_gastronomica::find($request->id);
        //$producto->update($request->except('_token'));
        if($request->nombre == null) { return redirect()->back()->withError('El nombre no puede estar vacío.'); }
        $producto = Productos_oferta_gastronomica::find($request->id);
        $producto->nombre = $request->nombre;
        $producto->save();

        DB::table('categorias_productos')->where('id_producto', $request->id_producto)->delete();

        foreach($request->categorias as $cat) {
            DB::table('categorias_productos')->insert(['id_producto' => $request->id_producto, 'id_categoria' => $cat]);
        }

        return redirect(route('admin.oferta_gastronomica'))->withSuccess('El producto se ha editado correctamente');
    }

    public function ocultar_mostrar_oferta_gastronomica (Request $request) {
        $producto = Productos_oferta_gastronomica::find($request->id);
        $producto->update(['visible' => !$producto->visible]);

        return redirect(route('admin.oferta_gastronomica'))->withSuccess('Se ha ocultado el producto. No le aparecerá al comercial.');
    }

    public function enviar_oferta_gastronomica(Request $request)
    {
        $boda = Boda::find($request->id);
        if(!isset($request->selecciones))
            return redirect()->back()->withError('Es necesario marcar los productos necesarios para poder enviar');

        $seleccion = Selecciones_comercial_oferta_gastronomica::create([
            'id_boda' => $boda->id,
            'selecciones' => serialize($request->selecciones),
            'type' => $request->type
        ]);
        
        // $mail_string = '<div class="lh">¡Hola '.$boda->name.'!, <br>Ya está disponible la oferta gastronómica.<br>';
        // $mail_string .= 'Puedes acceder desde el siguiente enlace:<br>';
        // $mail_string .= '<br>';

        if($request->type == 'pre') {
            $data = getMail('oferta_gastronomica');
            $activity = 'Prueba de menú enviada';
        } elseif($request->type == 'final') {
            $data = getMail('seleccion_final');
            $activity = 'Selección final enviada';
        }
        
        $mail = [
            'name' => $boda->name,
            'title' => $data->title,
            'msg' => $data->msg,
            'btn' => $data->btn,
            'url' => route($data->url, ['token' => $boda->token, 'id' => $seleccion->id])
        ];

        Mail::to($boda->email)->send(new Notif($mail));

        addActivity($activity, $boda->id, user()->id);

        return redirect(route('admin.bodas.ver', ['id' => $boda->id]))->withSuccess('Se ha enviado la selección a los novios. Te llegará una notificación cuando la completen.');
    }

    public function enviar_oferta_final_gastronomica(Request $request) {
        $boda = Boda::find($request->id);
        $sel = Selecciones_usuario_oferta_gastronomica::find($request->id_seleccion);
        $seleccion = Selecciones_comercial_oferta_gastronomica::create([
            'id_boda' => $boda->id,
            'selecciones' => $sel->selecciones,
            'type' => 'final'
        ]);
        
        $mail_string = '<div class="lh">¡Hola '.$boda->name.'!, <br>Ya está disponible la selección final de la oferta gastronómica.<br>';
        $mail_string .= 'Puedes acceder desde el siguiente enlace:';
        $mail_string .= '<br>';

        $data = getMail('seleccion_final');

        $mail = [
            'name' => $boda->name,
            'title' => $data->title,
            'msg' => $data->msg,
            'btn' => $data->btn,
            'url' => route($data->url, ['token' => $boda->token, 'id' => $seleccion->id])
        ];

        Mail::to($boda->email)->send(new Notif($mail));

        addActivity('Selección final enviada', $boda->id, user()->id);

        return redirect(route('admin.bodas.ver', ['id' => $boda->id]))->withSuccess('Se ha enviado la selección a los novios. Te llegará un email cuando ellos completen su selección.');
    }

    public function reenviar_oferta_gastronomica(Request $request) {
        $seleccion = Selecciones_comercial_oferta_gastronomica::find($request->id);
        
        $mail_string = '<div class="lh">¡Hola '.$seleccion->boda->name.'!<br>Ya está disponible la oferta gastronómica.<br>';
        $mail_string .= 'Puedes acceder desde el siguiente enlace para seleccionar los productos<br>';
        $mail_string .= 'finales.<br>';

        $mail = [
            'name' => $seleccion->boda->name,
            'title' => 'Completa la selección de la oferta gastronómica.',
            'msg' => $mail_string,
            'btn' => 'Completar oferta gastronómica',
            'url' => route('admin.bodas.completar_oferta_gastronomica', ['token' => $seleccion->boda->token, 'id' => $seleccion->id])
        ];

        Mail::to($seleccion->boda->email)->send(new Notif($mail));

        return redirect()->back()->withSuccess('Se ha enviado la selección a los novios. Te llegará un email cuando ellos completen su selección.');
    }

    public function nueva_oferta_gastronomica(Request $request) {
        $boda = Boda::find($request->id);
        $categorias_productos = Categorias_oferta_gastronomica::all();

        return view('admin.bodas.nueva_oferta_gastronomica', compact('boda', 'categorias_productos'));
    }

    public function bodas_completar_oferta_gastronomica(Request $request) {
        $seleccion = Selecciones_comercial_oferta_gastronomica::find($request->id);
        $boda = Boda::where('token', $request->token)->first();

        if ($seleccion->seleccion_usuario) {
            $mensaje = 'Se ha enviado tu selección correctamente. En breve recibirás una respuesta del comercial.';
            return view('front.datos-enviados', compact('mensaje'));
        }

        return view('front.completar_oferta_gastronomica', compact('seleccion', 'boda'));
    }

    public function bodas_completar_oferta_gastronomica_enviar(Request $request) {
        $selecciones_usuario = Selecciones_usuario_oferta_gastronomica::create([
            'id_seleccion_com' => $request->id,
            'selecciones' => serialize($request->selecciones),
            'type' => $request->type
        ]);

        $seleccion_com = Selecciones_comercial_oferta_gastronomica::find($request->id);
        $seleccion_com->completado = 1;
        $seleccion_com->save();

        $boda = $selecciones_usuario->selecciones_comercial->boda;

        $mail_string = '<div class="lh">Ya está disponible la selección de la oferta gastronómica de la boda<br>';
        $mail_string .= 'con referencia '. $boda->referencia .'.<br>';
        $mail_string .= 'Puedes acceder desde el siguiente enlace:<br>';

        $mail = [
            'name' => $boda->name,
            'title' => 'Selección de oferta gastronómica completada',
            'msg' => $mail_string,
            'btn' => 'Ver selección',
            'url' => route('admin.bodas.ver_seleccion_usuario_oferta', ['id' => $boda->id, 'id_seleccion' => $selecciones_usuario->selecciones_comercial->id])
        ];

        foreach($boda->coms as $com) {
            Mail::to($com->email)->send(new Notif($mail));
        } 
        \Auth::login($boda->novios);

        addActivity('Prueba de menú seleccionada', $boda->id, user()->id);
        
        return redirect(route('user.gastronomia'))->withSuccess('Selección enviada correctamente al comercial, puedes revisarla desde aquí.');
    }

    public function ver_seleccion_usuario_oferta(Request $request, $id, $id_seleccion) {
        $boda = Boda::find($request->id);
        $grupo = $boda->grupoOfertas;
        $seleccion = Selecciones_usuario_oferta_gastronomica::find($id_seleccion);
        $seleccion_com = $seleccion->selecciones_comercial;
        $selecciones = $seleccion->values;
        $selecciones_com = unserialize($seleccion_com->selecciones);
        $categorias = Categorias_oferta_gastronomica::all();
        
        return view('admin.bodas.ver_seleccion_usuario', compact('boda', 'seleccion', 'seleccion_com', 'categorias', 'selecciones', 'selecciones_com', 'grupo'));
    }

    public function planos() {
        $planos = Plano::all();
        return view('admin.planos')->with(compact('planos'));
    }

    public function planos_crear(Request $request) {
        $name = $request->name;
        $uploadedFile = $request->file('files');
        if($request->file('files') == null) { return redirect()->back()->withError('Es necesario seleccionar un plano en formato PDF'); }
        $ext = $uploadedFile->getClientOriginalExtension();
        
        if($ext != 'pdf') // Check ext
            return redirect()->back()->withError('El formato del archivo debe ser ".pdf"');
        
        $plano = new Plano();
        $plano->name = $request->name;
        $plano->place_id = $request->place_id;
        $plano->comensales = $request->comensales;
        $plano->save();

        $filename = $plano->id.'.'.$ext;

        $file_url = Storage::disk('public')->putFileAs(
            'planos/',
            $uploadedFile,
            $filename
        );      

        return redirect(route('admin.planos'))->withSuccess('Plano creado correctamente');
    }

    public function planos_borrar($id) {
        $plano = Plano::find($id)->delete();
        $bodas = Boda::where('plano_id', $id)->update(['plano_id' => null]);
        return redirect(route('admin.planos'))->withSuccess('Plano borrado correctamente');
    }

    public function mesas(Request $request) {
        $plano = Plano::find($request->id);
        return view('admin.mesas')->with(compact('plano'));
    }

    public function mesas_imprimir(Request $request, $id) {
        $boda = Boda::find($request->id);
        $mesas = $boda->plano->mesas;
        $data = compact('boda', 'mesas');
        $pdf = Pdf::loadView('admin.mesas.imprimir', $data);
        //return $pdf->download('invoice.pdf');
        return $pdf->stream($boda->name.'_MESAS.pdf');
        //return view('admin.mesas.imprimir')->with($data);
    }

    public function mesas_generar(Request $request) {
        $boda = Boda::find($request->boda_id);
        
        for($i = 0; $i <= $request->mesas; $i++) {
            $mesa = new Mesa();
            $mesa->ref = null;
            $mesa->amount = $request->comensales;
            $mesa->plano_id = $request->plano_id;
            $mesa->boda_id = $request->boda_id;
            $mesa->save();
        }

        return redirect()->back()->withSuccess('Mesas generadas correctamente');
    }

    public function mesas_crear(Request $request) {
        for($i = 1; $i <= $request->amount; $i++) {
            $mesa = new Mesa();
            $mesa->ref = 'Mesa '.$i;
            $mesa->amount = 5;
            $mesa->plano_id = $request->plano_id;
            $mesa->boda_id = null;
            $mesa->save();
        }

        return redirect()->back()->withSuccess('Mesas creadas correctamente');
    }

    public function mesas_guardar(Request $request) {
        foreach($request->mesas as $id => $amount) {
            $mesa = Mesa::find($id);
            $mesa->amount = $amount;
            $mesa->save();
        }

        $string = 'Mesas guardadas correctamente';

        return redirect()->back()->withSuccess($string);
    }

    public function gastronomia(Request $request) {
        
    }

    public function bodas_editar_plano(Request $request) {
        $boda = Boda::find($request->boda_id);
        $boda->update(['plano_id' => $request->plano_id]);
        $invitados = Invitado::whereIn('id', $boda->invitados->pluck('id'))->update(['mesa_id' => null]);

        $mail_string = '<div class="lh">¡Hola '.$boda->name.'!<br>Ya es posible organizar las mesas de los invitados. Puedes acceder desde el panel o a través del siguiente enlace:</div>';
        
        $mail = [
            'name' => $boda->name,
            'title' => 'Ya puedes organizar las mesas para los invitados',
            'msg' => $mail_string,
            'btn' => 'Organizar mesas',
            'url' => route('user.mesas')
        ];

        Mail::to($boda->email)->send(new Notif($mail));

        addActivity('Plano seleccionado', $boda->id, user()->id);

        return redirect()->back()->withSuccess('Plano seleccionado correctamente, los novios serán notificados.');
    }

    public function bodas_editar_grupo_ofertas(Request $request) {
        $boda = Boda::find($request->boda_id);
        $boda->update(['grupo_ofertas_id' => $request->grupo_ofertas_id]);

        addActivity('Grupo ofertas seleccionado', $boda->id, user()->id);

        return redirect()->back()->withSuccess('Grupo de ofertas guardado correctamente');
    }

    public function emails() {
        $mails = Email::all();
        return view('admin.emails')->with(compact('mails'));
    }

    public function emails_edit($id) {
        $mail = Email::find($id);
        if($mail == null) { return redirect()->back()->withError('No existe éste mail'); }
        return view('admin.emails.edit')->with(compact('mail'));
    }

    public function emails_edit_enviar(Request $request) {
        $mail = Email::find($request->id);
        $mail->update($request->except('_token'));
        $mail->save();
        return redirect()->route('admin.emails')->withSuccess('Email guardado correctamente.');
    }

    public function emails_preview($id) {
        $mail = Email::find($id);
        if($mail == null) { return redirect()->back()->withError('No existe éste mail'); }
        return view('admin.emails.preview')->with(compact('mail'));
    }

    public function ver_politica_privacidad(){
        return view('admin.documentos.politica');
    }
}
