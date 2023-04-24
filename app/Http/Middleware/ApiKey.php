<?php

namespace App\Http\Middleware;

use Closure;
use Response;
use Illuminate\Http\Request;

class ApiKey
{
  public function handle(Request $request, Closure $next)
  {
    $api_key = $request->header('x-api-key');

    if ($api_key === env('APP_API_KEY')) {
      return $next($request);
    }

    return Response::json([
      'code' => '403',
      'message' => 'Not authorized',
    ], 403);
  }
}
