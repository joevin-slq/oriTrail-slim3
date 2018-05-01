<?php

namespace App\Middleware;

class CorsMiddleware
{
    public function __invoke($request, $response, $next) {

      $response = $next($request, $response);

      return $response->withHeader('Access-Control-Allow-Origin', '*');
    }
}
