<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;

class Controller {

	private $container;
	protected $database;

	public function __construct($container) {
		$this->container = $container;
		$this->database = $this->container->get('db');
	}

	public function render(ResponseInterface $response, $file, $data) {
		$this->container->view->render($response, $file, $data);
	}
}
