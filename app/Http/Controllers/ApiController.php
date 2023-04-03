<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Categorias_oferta_gastronomica;
use App\Models\User;

class ApiController extends Controller
{

    public function auth_login(Request $request) {
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if(Auth::attempt($credentials)) {
            $user = User::where('username', $request['username'])->first();
            $url = route('api.autologin', ['id' => $user->id]);
            return response()->json(['id' => $user->id, 'email' => $user->email, 'url' => $url], 200);
        }

        return 'false';
    }

    public function auth_autologin($id) {
        Auth::loginUsingId($id);
        return redirect()->route('root');
    }

    public function user_bodas() {
        $remap_attrs = ['name' => 'title'];
        $array = auth()->user()->bodas->map(function($boda){
            return [
            'title' => $boda->name,
            'date' => $boda->date,
            ];
        })->toArray();
        

        return $array;
    }

    public function user_update_token(Request $request) {
		return $request->token;
        $user = User::where('email', $request->email)->first(); if($user == null): return 'No existe'; endif;
        $user->onesignal_token = $request->token;
        $user->save();


        return 'true';
    }
	
	public function user_update_token_get($email, $token) {
        $user = User::where('email', $email)->first(); if($user == null): return 'No existe'; endif;
        $user->onesignal_token = $token;
        $user->save();

        return 'true';
	}
	
    public function categorias() {
        return Categorias_oferta_gastronomica::all();
    }
}
