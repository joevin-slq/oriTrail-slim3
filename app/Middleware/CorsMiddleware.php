<?php

namespace App\Middleware;

class CorsMiddleware
{
    public function __invoke($request, $response, $next) {

      $response = $next($request, $response);

      return $response->withHeader('Access-Control-Allow-Origin', '*')
          ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
          ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
}
