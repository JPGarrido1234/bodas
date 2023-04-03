<?php

namespace App\Http\Controllers;
use App\Models\Boda;
use App\Models\Cobro;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\Notif;


class CobrosController extends Controller
{
    public function index() {
        if(user()->rol != 'admin') {
            $bodas = \DB::table('bodas_coms')->where('com_id', user()->id)->pluck('boda_id');
        } else {
            $bodas = Boda::all()->pluck('id');
        }

        $facturas = Cobro::whereIn('boda_id', $bodas)->get();
        
        return view('admin.cobros', compact('facturas'));
    }

    public function add() {
        $user = user();
        $bodas = ($user->rol == 'com') ? $user->bodas : Boda::all();
        return view('admin.cobros.add', compact('bodas'));
    }

    public function add_submit(Request $request) {
        $factura = new Cobro();
        $factura->total = $request->total;
        $factura->date = $request->date ?? null;
        $factura->status = $request->status;
        $factura->boda_id = $request->boda_id;
        $factura->type = $request->type;
        $factura->concepto = $request->concepto;
        $factura->save();
        
        $boda = Boda::find($request->boda_id);
        
        // Guardar comprobante si existe
        self::upload($request->file('files'), $boda->token, $factura->id);

        // Enviar notificación
        if($request->has('notificacion') && $request->notificacion == 'on') {
            self::send_mail($boda->id);
            return redirect()->route('admin.bodas.ver', ['id' => $factura->boda_id])->withSuccess('Pago añadido y enviado correctamente.');
        }

        return redirect()->route('admin.bodas.ver', ['id' => $factura->boda_id])->withSuccess('Pago añadido correctamente.');
    }

    public function notificacion($boda_id) {
        self::send_mail($boda_id);

        return redirect()->back()->withSuccess('La notificación ha sido enviada correctamente.');
    }

    public function send_mail($boda_id) {
        $boda = Boda::find($boda_id);
        if($boda == null) { return false; }

        $data = getMail('cobro_pendiente');

        // $mail_string = '<div class="lh">¡Hola '.$boda->name.'!<br>Ante todo, muchas gracias por confiar en nosotros para un momento tan especial.<br>';
        // $mail_string .= '<br>Éstos son los datos para acceder a la aplicación:<br>';
        // $mail_string .= 'Usuario: <strong>'.$boda->email.'</strong><br>';
        // $mail_string .= 'Contraseña: <strong>'.$passwd.'</strong><br>';
        // $mail_string .= '<br>A continuación, detallamos la información necesaria para realizar la reserva:<br>';
        // $mail_string .= '<br>TITULAR: CAMPOS DE CÓRDOBA S.A.<br>';
        // $mail_string .= 'BANCO: BANKINTER<br>';
        // $mail_string .= 'IBAN: ES1001289471860100011282<br>';
        // $mail_string .= 'SWIFT: BKBKESMMXXX<br>';
        // $mail_string .= 'CONCEPTO: ' . $boda->concepto;
        // $mail_string .= 'CANTIDAD: 2.000€<br><br>';
        // $mail_string .= 'Es necesario que nos facilitéis vuestros datos personales a través del siguiente enlace:</div>';

        $mail = [
            'name' => $boda->name,
            'title' => $data->title,
            'msg' => $data->msg,
            'btn' => $data->btn,
            'url' => route($data->url)
        ];

        Mail::to($boda->email)->send(new Notif($mail));
    }

    public function justificante($id) {
        $factura = Cobro::find($id);
        $user = user();
        $bodas = ($user->rol == 'com') ? $user->bodas : Boda::all();
        
        return view('admin.cobros.ver', compact('factura', 'bodas'));
    }

    public function upload($file, $token, $name) {
        if($file != '' && $file != null) {
            $ext = $file->getClientOriginalExtension();
            if($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'pdf') {
                return redirect()->back()->withError('El archivo debe tener el formato correcto: JPG, PNG o PDF');
            }

            $fileName = $name.'.'.$file->getClientOriginalExtension();

            Storage::disk('public')->putFileAs('ingreso/'.$token, $file, $fileName);
        }
    }

    public function add_justificante(Request $request) {
        $cobro = Cobro::find($request->cobro_id);
        $file = $request->file('files');
        if($file == null OR $file == '') {
            return redirect()->back()->withError('No se ha encontrado el justificante, comprueba seleccionar el archivo correcto.');
        } else {
            self::upload($file, $cobro->boda->token, $cobro->id);
            $cobro->date = $request->date;
            // $cobro->percentage = $request->percAmount;
            $cobro->status = 'completed';
            $cobro->save();

            return redirect()->back()->withSuccess('Justificante añadido correctamente');
        }
    }

    public function edit(Request $request) {
        $factura = Cobro::find($request->id);

        $file = $request->file('files');
        if(user()->rol != 'user'):
            $factura->total = $request->total;
            $factura->status = $request->status;
            $factura->concepto = $request->concepto;
        else:
            if($request->date == null)
                return redirect()->back()->withError('No se ha seleccionado la fecha de ingreso.');
            
            if($file == null OR $file == '')
                return redirect()->back()->withError('No se ha subido ningún comprobante bancario.');
        endif;

        
        if($file != '' && $file != null) {
            self::upload($request->file('files'), $factura->boda->token, $factura->id);
        }

        $factura->date = $request->date;

        // Marcar como completado si existe fecha y comprobante1
        if($file != null && $file != '' && $request->date != null) { $factura->status = 'completed'; }
        $factura->save();

        if(user()->rol == 'user')
            return redirect()->route('user.facturacion')->withSuccess('El pago se ha guardado correctamente.');

        return redirect()->route('cobros.ver', ['id' => $factura->id])->withSuccess('El cobro se ha guardado correctamente.');
    }

    public function delete() {
        
    }
}
