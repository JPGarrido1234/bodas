<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Models\Place;
use App\Models\Boda;
use App\Models\Doc;
use App\Models\DocSigned;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompleteData;
use App\Mail\SignDoc;
use App\Mail\Notif;

class ComercialController extends Controller
{
    public function resumen() {
        $bodas = auth()->user()->bodas->sortBy('date');
        if($bodas == '[]') { return redirect()->route('admin.bodas.crear'); }
        return view('com.resumen')->with(compact('bodas'));
    }

    public function bodas() {
        $bodas = auth()->user()->bodas;
        return view('admin.bodas')->with(compact('bodas'));
    }

    public function bodas_crear() {
        return view('admin.bodas.crear');
    }

    public function bodas_ver($id) {
        $boda = Boda::find($id);
        $docs = Doc::all();
        return view('admin.bodas.ver')->with(compact('boda', 'docs'));
    }

    public function novedades() {
        $activities = (\Auth::check()) ? \Auth::user()->activities : [];
        return view('com.novedades', compact('activities'));
    }
/*
    public function documento(Request $request){
        $chat_id = $request->chat_id;
        $user_id = $request->user_id;
        $uploadedFile = $request->file('files');

        if($uploadedFile != null){
            $name = $uploadedFile->getClientOriginalName();

            $name_original = str_replace(' ', '_', $name);
            $filename = $chat_id.'_'.$user_id.'_'.$name_original;

            try{
                $files = File::files(storage_path('app/public/comercial/'));
                if(count($files) > 0){
                    foreach($files as $file){
                        if(file_exists($file)){
                            $ver = explode('_', basename($file));
                            if(count($ver) > 2){
                                $ver_chat_id = $ver[0];
                                if((int)$ver_chat_id == $request->chat_id){
                                    if(File::exists($file)){
                                        File::delete($file);
                                    }   
                                }
                            }
                        }   
                    }
                } 
            }catch(\Exception $e){

            }

            Storage::disk('public')->putFileAs(
                'comercial/',
                $uploadedFile,
                $filename
            );   
            
            return redirect()->back()->withSuccess('Documento subido correctamente.');
        }

        return redirect()->back();
    }
    */
}
