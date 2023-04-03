<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Boda;
use App\Models\GrupoInvitados;
use App\Models\Invitado;
use App\Models\Nombregrupoinvitado;

class GuestController extends Controller
{
    public function guests() {
        return redirect('/');
    }

    public function guests_confirmar($token) {
        $boda = Boda::where('token', $token)->first();
        if($boda == null) 
            return redirect('/');

        return view('guests.confirmar')->with(compact('boda'));
    }

    public function guests_delete($token, $id) {
        $inv = Invitado::find($id);
        if($token != user()->boda->token || $inv == null) { return redirect()->back()->withError('No es posible realizar esta acción'); } 
        $inv->delete();
        return redirect()->back()->withSuccess('El invitado ha sido eliminado correctamente');
    }

    public function guests_confirmar_enviar(Request $request) {
        $boda = Boda::where('token', $request->token)->first();
        if($boda == null){ return redirect('/')->withError('No es posible mostrar el contenido.'); }

        //$name = Nombregrupoinvitado::where('boda_id', $boda->id)->where('name_grupo', $request->nuevo_grupo)->get();
        //if(count($name) == 0){
            $grupo_name_invitado = new Nombregrupoinvitado();
            $grupo_name_invitado->name_grupo = $request->nuevo_grupo;
            //$grupo_name_invitado->boda_id = $boda->id;
            $grupo_name_invitado->save();
        //}

        $grupo = new GrupoInvitados();
        $grupo->boda_id = $boda->id;
        $grupo->name_grupo_id = $grupo_name_invitado->id;
        $grupo->save();

        $inv_principal = new Invitado();
        $inv_principal->name = $request->nombre;
        $inv_principal->apellido1 = $request->apellido1;
        $inv_principal->apellido2 = $request->apellido2;
        $inv_principal->apellidos = $request->apellido1.' '.$request->apellido2;
        $inv_principal->email = $request->email;
        $inv_principal->boda_id = $boda->id;
        $inv_principal->grupo_id = $grupo->id;
        $inv_principal->principal = true;
        $inv_principal->tipo = 'adulto';
        $inv_principal->confirm = $request->confirm;
        $inv_principal->alergias = $request->alergias;
        $inv_principal->save();

        if(isset($request->guests) && count($request->guests) > 0) {
            foreach($request->guests as $guest) {
                $guest = json_decode($guest);
                $invitado = new Invitado();
                $invitado->name = $guest->name;
                $invitado->apellidos = $guest->apellido1.' '.$guest->apellido2;
                $invitado->apellido1 = $guest->apellido1;
                $invitado->apellido2 = $guest->apellido2;
                $invitado->email = ($guest->email == '---') ? null : $guest->email;
                $invitado->alergias = ($guest->alergias == '') ? null : $guest->alergias;
                $invitado->boda_id = $boda->id;
                $invitado->grupo_id = $grupo->id;
                $invitado->principal = false;
                $invitado->tipo = $guest->tipo;
                $invitado->confirm = $request->confirm;
                $grupo->update(['name_grupo_id' => $grupo_name_invitado->id]);
                $invitado->save();
            }
        }

        return view('alert')->with(['title' => 'Datos enviados correctamente', 'message' => '¡Gracias! Los novios serán notificados con los datos proporcionados.']);
    }
}
