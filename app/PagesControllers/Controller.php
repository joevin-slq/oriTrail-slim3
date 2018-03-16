<?php
namespace App\PagesControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

// controleur dédié au Front-End
class Controller {

	private $container;
	protected $pdo;

	public function __construct($container) {
		$this->container = $container;
		$this->pdo = $this->container->get('pdo');
	}

	public function render(ResponseInterface $response, $file, $data) {
		$this->container->view->render($response, $file, $data);
	}

	public function home(RequestInterface $request, ResponseInterface $response) {
		$this->render($response, 'pages/home.twig', []);
	}
}
