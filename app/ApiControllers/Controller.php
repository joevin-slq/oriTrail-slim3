<?php
namespace App\ApiControllers;

use Psr\Http\Message\ResponseInterface;

// controleur dédié à l'API REST
class Controller {

	public function __construct($container) {
		$this->container = $container;
	}
	
}
