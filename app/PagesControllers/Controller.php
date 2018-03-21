<?php
namespace App\PagesControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

// controleur dédié au Front-End
class Controller {

	private $container;
	protected $router;

	public function __construct($container) {
		$this->container = $container;
		$this->router = $this->container->get('router');
	}

	public function render(ResponseInterface $response, $file, $data) {
		$this->container->view->render($response, $file, $data);
	}

	public function home(RequestInterface $request, ResponseInterface $response) {
		$this->render($response, 'pages/home.twig', [
        	'page' => "home"
		]);
	}
}
