<?php
namespace App\PagesControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

// controleur dédié au Front-End
class Controller {

	private $container;
	protected $router;
	protected $validator;
	protected $csrf;
	protected $auth;
	protected $flash;

	public function __construct($container) {
		$this->container = $container;
		$this->router = $this->container->get('router');
		$this->validator = $this->container->get('validator');
		$this->csrf = $this->container->get('csrf');
		$this->auth = $this->container->get('auth');
		$this->flash = $this->container->get('flash');
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
