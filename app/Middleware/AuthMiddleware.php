<?php

namespace App\Middleware;

class AuthMiddleware extends Middleware{

      public function __invoke($request, $response, $next)
      {
        // vérifie si l'utilisateur n'est pas connecté
        if(!$this->container->auth->check()) {
          // message flash
          $this->container->flash->addMessage('error', 'Connectez-vous pour accéder à cette page.');
          // redirection
          return $response->withRedirect($this->container->router->pathFor('signin'));
        }

      	$response= $next($request,$response);
      	return $response;
      }
}
