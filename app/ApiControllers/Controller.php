<?php
namespace App\ApiControllers;

use Psr\Http\Message\ResponseInterface;

// controleur dédié à l'API REST
class Controller {

	protected $apiauth;

	public function __construct($container) {
		$this->container = $container;
		$this->apiauth = $this->container->get('apiauth');
	}

}
