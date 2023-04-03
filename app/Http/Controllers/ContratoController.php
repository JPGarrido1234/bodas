<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\Doc;
use App\Models\ContratoField;
use App\Models\Boda;
use App\Models\Cobro;
use App\Models\Email;
use App\Models\DatosFacturacion;
use App\Models\DocSigned;
use App\Models\Activity;
use App\Mail\SignDoc;
use App\Mail\Notif;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ContratoController extends Controller
{

    public function editar($id) {
        if($id == null): return redirect()->back()->withError('No se ha encontrado el contrato solicitado'); endif;
        $doc = Doc::find($id);
        $file = new \PhpOffice\PhpWord\TemplateProcessor(substr($doc->public_url, 1));
        $db_fields = $doc->fields->toArray();
        $file_fields = $file->getVariables();

        //Diferencia si es un tipo de contrato de cierre o si no, sólo añadirlo si es documento tipo contrato y es de cierre
        $dato_decoracion = [
            'name' => 'decoracion',
            'doc_id' => $doc->id,
            'show_name' => 'Decoración'
        ];

        foreach($file_fields as $field){
            if($field == '_decoracion'){
                $campoDecoracion = ContratoField::where('name', 'decoracion')->where('doc_id', $doc->id)->first();
                if($campoDecoracion == null){
                    ContratoField::create($dato_decoracion);
                }
            }
        }
        

        sort($file_fields);
        sort($db_fields);
        $file_fields = array_reverse($file_fields);
        $db_fields = array_reverse($db_fields);
        $db_fields_splitted = (count($db_fields) != 0) ? $db_fields : [];
        $file_fields_splitted = (count($file_fields) != 0) ? splitVariables($file_fields) : [];   

        return view('admin.contratos.editar')->with(compact('doc', 'file_fields', 'db_fields', 'db_fields_splitted', 'file_fields_splitted'));
    }

    public function crear_campos(Request $request) {
        $doc_id = $request->doc_id;
        $fields = $request->except('doc_id', '_token');
        foreach($fields as $key => $field) {
            //echo $field . ' - '. $doc_id .'<br>';
            ContratoField::create(['doc_id' => $doc_id, 'name' => $key, 'show_name' => $field]);
        }

        return redirect()->route('admin.documentos')->withSuccess('Campos guardados correctamente en contrato');
    }

    public function borrador($id, $doc_id) {
        ini_set('memory_limit', '300M');
        $boda = Boda::find($id);
        $doc = Doc::find($doc_id);
        $cobro = Cobro::where('boda_id', $id)->where('type', 'contrato_celebracion')->first();
        $template = new \PhpOffice\PhpWord\TemplateProcessor(substr($doc->public_url, 1));
        $file_fields = $template->getVariables();
        $content = '';
        $path = 'storage/contratos/'.$boda->id.'/';

        if($boda == null OR $doc == null) {return redirect()->back()->withError('La boda o el documento no existe'); } // Comprobar boda y doc
        if(!file_exists($path)){ mkdir($path, 0755, true); } // Crear directorio si no existe
        $fields = $doc->fields;
        $datos = $boda->datos;
        $adicional = [];
        foreach($fields as $field) {
            $adicional[$field->name] = htmlspecialchars($field->value($id));
        }

        setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');

        $fecha_actual = Carbon::now();

        $cobros = Cobro::where('boda_id', $boda->id)->get();
        if($cobros == null || count($cobros) == 0){
            return redirect()->back()->withError('Aún no dispone de cobros, no puede enviar el documento.');
        }

        
        foreach($file_fields as $field){
            if($field == '_datos_facturacion'){
                $datos_facturacion = DatosFacturacion::where('boda_id', $boda->id)->get();
                if(count($datos_facturacion) == 0){
                    return redirect()->back()->withError('Aún no dispone de datos de facturación, no puede enviar el documento.');
                }
                $cont = 1;
                if($datos_facturacion != null){
                    foreach($datos_facturacion as $factura){
                        $content .= "Factura nº ".$cont++." : \n";
                        $content .= "Nombre: ".$factura->name."\r\n";
                        $content .= "NIF: ".$factura->nif."\n";
                        $content .= "Direccion: ".$factura->address."\n";
                        $content .= "Ciudad: ".$factura->city."\n";
                        $content .= "País: ".$factura->country."\n";
                        $content .= "Cod. Postal: ".$factura->cp."\n";
                        $content .= "Email: ".$factura->email."\n";
                        $content .= "Teléfono: ".$factura->tlf."\n";
                        $content .= "Porcentaje : ".$factura->percentage."\n";
                    }
                }
            }
        }
            
        // PREDEFINIDAS
        $adicional['_name'] = $datos->nombre_1.' '.$datos->apellidos_1. ' / '. $datos->nombre_2.' '.$datos->apellidos_2;
        $adicional['_nombre'] = $datos->nombre_1.' '.$datos->apellidos_1. ' / '. $datos->nombre_2.' '.$datos->apellidos_2;
        $adicional['_nombre_1'] = $datos->nombre_1.' '.$datos->apellidos_1;
        $adicional['_nombre_2'] = $datos->nombre_2.' '.$datos->apellidos_2;
        $adicional['_dia'] = date('d');
        $adicional['_lugar'] = ($boda->place_id == 3 && $boda->valor_lugar_catering != null) ? $boda->valor_lugar_catering->valor  : $boda->place->name;
        $adicional['_domicilio_1'] = $datos->direccion_1;
        $adicional['_domicilio_2'] = $datos->direccion_2;
        $adicional['_comerciales'] = '';
        $adicional['_dni'] = $datos->dni_1;
        $adicional['_dni_1'] = $datos->dni_1;
        $adicional['_dni_2'] = $datos->dni_2;
        $adicional['_mes'] = $fecha_actual->formatLocalized('%B');
        $adicional['_email'] = $boda->coms()->get()->first()->email;
        $adicional['_email_1'] = $datos->email_1;
        $adicional['_email_2'] = $datos->email_2;
        $adicional['_year'] = date('Y');
        $adicional['_tlf'] = $boda->tel;
        $adicional['_tlf_1'] = $datos->telefono_1;
        $adicional['_tlf_2'] = $datos->telefono_2;
        $adicional['_fecha'] = Carbon::parse($boda->date)->format('d/m/Y');
        $adicional['_fecha_evento'] = $adicional['_fecha'];
        $adicional['_fecha_ingreso'] = Carbon::parse($cobro->date)->format('d/m/Y');
        $adicional['_datos_facturacion'] = $content;
        
        $adicional['_hora_inicio'] = '';
        $adicional['_hora_fin'] = '';
        $adicional['_cubiertos_adulto'] = $boda->cubiertos_adultos;
        $adicional['_cubiertos_infantil'] = $boda->cubiertos_ninos;

        $adicional['_fecha_envio'] = $adicional['_dia'].' de '.$adicional['_mes'].' de '.date('Y');
        $adicional['_correo_comercial'] = auth()->user()->email;
        
        $adicional['_menu'] = '';
        $adicioanl['_adicional_menu'] = '';
        $adicional['_clausulas_adicionales'] = '';
        $adicional['_clausulas'] = '';

        // GUARDAR DOCX
        
        $file_vars = splitVariables($template->getVariables());
        $template->setImageValue('_firma_prestador', 'images/sello.jpg');

        try{
            $files = File::files(storage_path('app/public/contratos/'.$boda->id.'/'));
            if(count($files) > 0){
                foreach($files as $file){
                    if(file_exists($file)){
                        $ext = explode('.', $file->getFilename());
                        if(count($ext) > 1){
                            if($ext[1] == 'jpg' || $ext[1] == 'png'){
                                $value = Str::contains($file->getFilename(), '_imagen');
                                if($value == true){
                                    $template->setImageValue('_decoracion', public_path($path.$doc_id.'_imagen.'.$ext[1]));
                                }
                            }
                        }
                    }   
                }
            }  
        }catch(\Exception $e){

        }
        
        $template->setValues($adicional);
        $template->saveAs(public_path($path.$doc->id.'.docx'));

        // GUARDAR COPIA EN PDF
        DocToPDF(public_path($path.$doc->id.'.docx'), public_path($path));

        return view('admin.contratos.borrador')->with(compact('boda', 'doc', 'datos', 'adicional'));
    }
    
    public function enviar(Request $request) {
        $doc = Doc::find($request->doc_id);
        if($doc == null)
            return redirect()->back()->withErrors('No se encuentra el documento');

        $boda = Boda::find($request->boda_id);
        if($boda == null)
            return redirect()->back()->withError('No se encuentra boda con ID: '.$request->boda_id);

        // Guardar documento como enviado
        $doc_signed =  new DocSigned();
        $doc_signed->token = Str::random(12);
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

        Activity::create(['boda_id' => $boda->id, 'user_id' => user()->id, 'description' => 'Contrato enviado para firmar']);
    
        Mail::to($boda->email)->send(new Notif($data));
        return redirect(route('admin.bodas.ver', ['id' => $request->boda_id]))->withSuccess('El documento ha sido enviado correctamente');
    }

}
