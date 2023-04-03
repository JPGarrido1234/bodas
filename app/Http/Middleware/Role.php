<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Auth\Middleware\Role as Middleware;
use Illuminate\Support\Facades\Auth;

class Role {

  public function handle($request, Closure $next) {
    if (!Auth::check()) // This isnt necessary, it should be part of your 'auth' middleware
      return redirect(route('login'));

    if(in_array(auth()->user()->rol, ['admin', 'com', 'user']))
      return $next($request);

    return redirect(route('login'));
  }
}