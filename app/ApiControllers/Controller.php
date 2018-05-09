<?php
namespace App\ApiControllers;

use Psr\Http\Message\ResponseInterface;

// controleur dédié à l'API REST
class Controller {

	protected $apiauth;
	protected $validator;

	public function __construct($container) {
		$this->container = $container;
		$this->apiauth = $this->container->get('apiauth');
		$this->validator = $this->container->get('validator');
	}

}
