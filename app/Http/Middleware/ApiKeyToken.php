<?php

namespace App\Http\Middleware;

use DB;
use Closure;
use Response;
use Illuminate\Http\Request;

class ApiKeyToken
{
  public function handle(Request $request, Closure $next)
  {
    $api_key = $request->header('x-api-key');
    $token = $request->header('x-token');

    $user = DB::table('players')
      ->where('token', $token)
      ->first();

    if ($api_key === env('APP_API_KEY') && $user) {
      return $next($request);
    }

    return Response::json([
      'code' => '403',
      'message' => 'Not authorized',
    ], 403);
  }
}
