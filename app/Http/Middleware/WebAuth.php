<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;
use DB;
use Illuminate\Http\Request;

class WebAuth
{
  public function handle(Request $request, Closure $next)
  {
    $token = Cookie::get('token_auth');

    $user = DB::table('users')
      ->where('token', $token)
      ->first();

    if ($user) {
      return $next($request);
    }

    return redirect('/login');
  }
}
