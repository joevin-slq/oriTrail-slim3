<?php

namespace App\Middleware;
/*
 * Classe qui permet de sauvegarder les champs valides
 * d'un formulaire lorsque ce dernier est incomplet.
 */
class OldInputMiddleware extends Middleware{

      public function __invoke($request, $response, $next){

        if(isset($_SESSION['old'])){

      	    $this->container->view->getEnvironment()->addGlobal('old',$_SESSION['old']);
        }
      	$_SESSION['old']= $request->getParams();
      	$response= $next($request,$response);


      	return $response;
      }

}
