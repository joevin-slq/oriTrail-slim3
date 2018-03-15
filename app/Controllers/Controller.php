<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;

class Controller {

	private $container;
	private $database;

	public function __construct($container) {
		$this->container = $container;
		
		// here this is working too:
   		$database = $this->container->get('db');
   		$lieux = $database->query('SELECT * FROM Lieu');
   		var_dump($lieux);
	}

	public function render(ResponseInterface $response, $file) {
		$this->container->view->render($response, $file);
	}
}
