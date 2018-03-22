<?php

namespace App\Middleware;
/*
 * Classe qui permet de vérifier les champs d'un formulaire.
 */
class ValidationErrorsMiddleware extends Middleware{

      public function __invoke($request, $response, $next){

      	if (isset($_SESSION['errors']))
        {
          $this->container->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);
          unset($_SESSION['errors']);
        }

      	$response= $next($request,$response);

      	return $response;
      }
}
