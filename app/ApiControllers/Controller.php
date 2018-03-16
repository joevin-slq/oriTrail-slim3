<?php
namespace App\ApiControllers;

use Psr\Http\Message\ResponseInterface;

// controleur dédié à l'API REST
class Controller {
	protected $pdo;

	public function __construct($container) {
		$this->container = $container;
		$this->pdo = $this->container->get('pdo');
	}
}
