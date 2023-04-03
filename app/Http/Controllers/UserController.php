<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Models\Chat;
use App\Models\Message;
use App\Models\GrupoInvitados;
use App\Models\Nombregrupoinvitado;
use App\Models\Invitado;
use App\Models\Boda;
use App\Models\Mesa;
use App\Models\Activity;
use App\Mail\Notif;
use App\Models\DocSigned;
use App\Models\Alergeno;

class UserController extends Controller
{
    public function mi_boda() {
        $boda = auth()->user()->boda;
        return view('user.mi-boda')->with(compact('boda'));
    }

    public function datos() {
        $boda = auth()->user()->boda;
        return view('user.datos')->with(compact('boda'));
    }

    public function documentos() {
        if(auth()->user()->rol != 'user') { return redirect('/'); }
        $boda = auth()->user()->boda;
        $docs = $boda->docs->sortByDesc('created_at');
        $docs_firmar = $docs->where('type', 'global')->where('category_id', 1)->sortByDesc('created_at');
        $docs_visual = $docs->where('type', 'global')->whereNotIn('category_id', [1, 5]);
        $docs_otros = $docs->where('type', 'private');

        return view('user.documentos')->with(compact('boda', 'docs', 'docs_otros', 'docs_firmar', 'docs_visual'));
    }

    public function documentos_firmar($token) {
        $doc_sign = DocSigned::where('token', $token)->first();
        $boda = $doc_sign->boda;
        if($doc_sign == null)
            return redirect('/')->withError('No se encuentra el documento');

        return view('admin.documentos.firmar')->with(compact('doc_sign', 'boda'));
    }

    // public function documentos_firmar_enviar(Request $request) {
    //     $doc_sign = DocSigned::find($request->doc_sign);
    //     $base64 = $request->base64;
    //         if($base64 == null OR $base64 == '')
    //             return redirect(route('admin.documentos.firmar', ['token' => $doc_sign->token]))->with(['error' => 'Es necesario firmar el documento.']);
    //     return view('admin.documentos.sign')->with(compact('doc_sign', 'base64'));
    // }

    public function documentos_firmar_sign(Request $request) {
        $base64 = $request->base64;
        $doc_sign = DocSigned::find($request->doc_sign);

        // Guardar copia firma
        $png_url = $doc_sign->doc_id.'_firma.png';
        $path = public_path().'/storage/contratos/'. $request->boda_id . '/' . $png_url;
        Image::make(file_get_contents($base64))->save($path);     
        
        // Obtener documento
        $file = new \PhpOffice\PhpWord\TemplateProcessor('storage/contratos/'.$request->boda_id.'/'.$doc_sign->doc_id.'.docx');
        
        // Añadir imagen en variable
        $file->setImageValue('_firma_usuario', $path);
        
        // Guardar copia de documento en word
        $file->saveAs(public_path('storage/contratos/'.$request->boda_id.'/'.$doc_sign->doc_id.'_firmado.docx'));
        // Guardar copia de documento en PDF
        DocToPDF(public_path('storage/contratos/'.$request->boda_id.'/'.$doc_sign->doc_id.'_firmado.docx'), public_path('storage/contratos/'.$request->boda_id.'/'));


        // // Guardar documento en local
        // $base64_pdf = $request->pdfbytes;
        // $data = substr($base64_pdf, strpos($base64_pdf, ',') + 1);
        // $data = base64_decode($data);

        // Storage::disk('public')->put('signs/'.$doc_sign->boda_id.'/'.$doc_sign->doc_id.'_signed.pdf', $data);

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

        Activity::create(['boda_id' => $boda->id, 'user_id' => user()->id, 'description' => 'Nuevo documento firmado']);

        return redirect(route('user.documentos'))->withSuccess('Documento firmado y guardado correctamente. El comercial será notificado.');
    }

    public function mensajes() {
        $messages = null;
        $documento_subida = null;
        $ver_tipo_user = null;
        $files = null;
        $url_file_subida = null;
        $ext = null;
        $exts = null;
        $arr_ext = null;
        $arr_files = null;

        $chat = auth()->user()->boda->chat;
        
        if($chat != null) {
            $messages = $chat->messages;
            $new_msgs = $chat->new_messages;
            if($new_msgs->count() != 0) {
                Message::whereIn('id', $new_msgs->pluck('id'))->update(['readed' => 1]);
            }
                
                try{
                    $files_user = $files = File::files(storage_path('app/public/upload/'));
                    if(count($files_user) > 0){
                        foreach($files_user as $file){
                            if(file_exists($file)){
                                $ver = explode('_', basename($file));
                                if(count($ver) > 8){
                                    $ver_chat_id = $ver[6];
                                    $ver_user_id = $ver[7];
                                    if((int)$ver_chat_id == auth()->user()->boda->chat->id){
                                        if((int)$ver_user_id == auth()->user()->id){
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

                }catch(\Exception $e) {
                    
                }
            
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

    public function invitados() {
        $boda = user()->boda;
        $invitados = $boda->invitados;
        $alergenos = Alergeno::all();

        return view('user.invitados')->with(compact('boda', 'invitados', 'alergenos'));
    }

    public static function getGrupoInvitado($grupo_name_id){
        $grupoInvitado = GrupoInvitados::find($grupo_name_id);
        $grupo_name = Nombregrupoinvitado::where('id', $grupoInvitado->name_grupo_id)->get()->first();
        if(isset($grupo_name))
        return $grupo_name->name_grupo != null ? $grupo_name->name_grupo : '';
    }

    public function invitados_crear(Request $request) {

        $grupo = new GrupoInvitados();
        $grupo->boda_id = $request->boda_id;
        $grupo->save();
    
        $invitado = new Invitado();
        $invitado->name = $request->name;
        $invitado->apellidos = $request->apellido1.' '.$request->apellido2;
        $invitado->apellido1 = $request->apellido1;
        $invitado->apellido2 = $request->apellido2;
        $invitado->email = $request->email;
        $invitado->alergias = $request->alergias;
        $invitado->alergenos = serialize($request->alergenos);
        $invitado->boda_id = $request->boda_id;
        $invitado->tipo = $request->tipo;
        $invitado->grupo_id = $grupo->id;
        $invitado->principal = true;
        $invitado->confirm = ($request->confirm == 'null') ? null : $request->confirm;
        $invitado->save();

        return redirect()->back()->withSuccess('Invitado registrado correctamente');
    }

    public function invitados_alergenos_editar(Request $request) {
        $alergenos = serialize($request->alergenos);
        $invitado = Invitado::find($request->invitado_id);
        if($invitado == null) { return redirect()->back()->withError('El invitado no existe'); }
        $invitado->alergenos = $alergenos;
        $invitado->save();

        return redirect()->back()->withSuccess('Alérgenos actualizados correctamente');
    }

    public function mesas() {
        $boda = auth()->user()->boda;
        return view('user.mesas')->with(compact('boda'));
    }

    public function mesas_datos($token, $mesa_id = null) {
        $boda = Boda::where('token', $token)->first();
        $mesas = [];
        $grupos = [];
        $por_asignar = 0;
        $asignados = 0;
        $total = 0;
        $all_mesas = $boda->plano->mesas ?? [   ];

        // Comproabr mesa_id
        if($mesa_id == null)    
            $mesa_id = $all_mesas[0]->id;

        // Obtener mesas con count
        foreach($all_mesas as $key => $mesa) {
            $invs = $mesa->invitados;
            $value = $key+1;
            $count = ($invs != null) ? $invs->count() : 0;
            $mesa_name = $mesa->ref.' ('.$count.'/'.$mesa->amount.')';
            if($count == $mesa->amount) { $mesa_name .= ''; } // ICONO MESA COMPLETA
            $mesas[] = ['id' => $mesa->id, 'name' => $mesa_name, 'amount' => $mesa->amount, 'count' => $count];
        }

        // Invitados
        //$guests = Invitado::where('boda_id', $boda->id)->where('confirm', 'true')->orderBy('mesa_id', 'asc')->orderBy('name', 'asc')->orderBy('apellidos', 'asc')->get();
        $check_grupos = $boda->grupos;
        if($check_grupos != null) {
            foreach($check_grupos as $key => $grupo) {
                $grupo_invitados = [];
                $invitados = $grupo->invitados;
                if($invitados->count() > 0) {
                    foreach($invitados as $guest) {
                        if($guest->mesa_id != null) {
                            $nombre_mostrar = $guest->name.' '.$guest->apellidos.' ('.$guest->mesa->ref.')';
                        } else {
                            $nombre_mostrar = $guest->name.' '.$guest->apellidos;
                        }

                        if($guest->confirm != 'true')
                            continue;

                        $name = $guest->name;
                        if($guest->mesa_id != null) {
                            $asignados++;
                        }

                        if($guest->tipo == 'niño') {
                            $icon = ' <i '.tooltip().' class="fas fa-child" style="float: right;padding-top: 6px;"></i>';
                            $nombre_mostrar .= $icon;
                            $name .= $icon;
                        }

                        // Guardar invitado
                        $grupo_invitados[] = ['id' => $guest->id, 'name' => $name, 'apellidos' => $guest->apellidos, 'mesa_id' => $guest->mesa_id, 'nombre_mostrar' => $nombre_mostrar];
                        $total++; // Total
                    }
                    $grupos[] = ['id' => $grupo->id, 'name' => 'Grupo '.($key+1), 'invitados' => $grupo_invitados];
                }
            }
        }

        $por_asignar = $total - $asignados;
        $data = ['grupos' => $grupos, 'mesas' => $mesas, 'por_asignar' => $por_asignar, 'asignados' => $asignados, 'total' => $total, 'mesa_id' => $mesa_id];
        
        return $data;
    }

    public function invitados_mesa_update(Request $request) {
        // guests: guests,
        // mesa: mesa,
        // type: type,
        // token: token,
        foreach($request->guests as $guest) {
            
            if($request->type == 'add') {
                $inv = Invitado::find($guest)->update(['mesa_id' => $request->mesa]);
            }

            if($request->type == 'rem') {
                $inv = Invitado::find($guest)->update(['mesa_id' => null]);
            }
        }

        return 'true';
    }

    public function invitados_estado_update(Request $request) {
        $invitado = Invitado::find($request->id);
        switch ($request->estado) {
            case 'si':
                $invitado->confirm = 'true';
                break;
            
            case 'no':
                $invitado->confirm = 'false';
                $invitado->mesa_id = null;
                break;
            
            default:
                $invitado->confirm = null;
                $invitado->mesa_id = null;
                break;
        }

        $invitado->save();

        return redirect()->back()->withSuccess('La confirmación del invitado ha sido guardada');
    }

    public function update_fecha_ingreso(Request $request) {
        $boda = Boda::find($request->id);
        $boda->date_ingreso = $request->date_ingreso;

        $file = $request->file('files');
        $ext = $file->getClientOriginalExtension();

        if($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'pdf') {
            return redirect()->back()->withError('El archivo debe tener el formato correcto: JPG, PNG o PDF');
        }

        $fecha = \Carbon\Carbon::parse($boda->date_ingreso)->format('d/m/Y');

        $mail_string = 'Los novios han registrado la fecha de ingreso: <br><h4>'.$fecha.'</h4>';
        $mail = [
            'name' => $boda->name,
            'title' => 'Fecha de ingreso | '.$boda->ref,
            'msg' => $mail_string,
            'btn' => 'Acceder al panel',
            'url' => route('admin')
        ];
        
        Storage::disk('public')->put('ingreso/'.$boda->token.'/', $file);

        // $file_url = \Storage::disk('local')->putFileAs(
        //     public_path('ingreso'),
        //     $file,
        //     $filename
        // );

        $boda->save();

        Activity::create(['boda_id' => $boda->id, 'user_id' => user()->id, 'description' => 'Novios han indicado la fecha de ingreso']);

        foreach($boda->coms as $com) {
            Mail::to($com->email)->send(new Notif($mail));
        }

        return redirect()->back()->withSuccess('Se ha guardado el comprobante y la fecha de ingreso correctamente');
    }

    public function gastronomia(Request $request) {
        $selecciones_comercial = user()->boda->selecciones_comercial_oferta_gastronomica->groupBy('type');

        return view('user.gastronomia')->with(compact('selecciones_comercial'));
    }

    public function pagos() {
        
        $facturas = user()->boda->cobros;
        return view('admin.cobros')->with(compact('facturas'));
    }
}
