<?php

namespace App\Middleware;

class ApiAuthMiddleware extends Middleware{

      public function __invoke($request, $response, $next)
      {
        // récupère le token depuis l'entête
        $token = $this->fetchToken($request);

        // vérifie si l'utilisateur possède un token valide
        if(!$this->container->apiauth->checkToken($token)) {
          // message JSON
          return $response->withJson([
      			['status' => 'Veuillez vous connecter']
      		], 401);
        }

      	$response = $next($request,$response);
      	return $response;
      }

      public function fetchToken($request) {
         $header = $request->getHeader("Authorization");
         $regexp = "/Bearer\s+(.*)$/i";

         if (preg_match($regexp, $header[0], $matches)) {
             return $matches[1];
         }
      }
}
