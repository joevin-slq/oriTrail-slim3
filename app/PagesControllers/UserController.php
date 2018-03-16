<?php
namespace App\PagesControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController extends Controller {

	public function getLogin(RequestInterface $request, ResponseInterface $response) {
		$this->render($response, 'pages/login.twig', []);
	}
	public function postLogin(RequestInterface $request, ResponseInterface $response) {
		var_dump($request->getParams());
		die();
	}

	public function getSignin(RequestInterface $request, ResponseInterface $response) {
		$this->render($response, 'pages/signin.twig', []);
	}
	public function postSignin(RequestInterface $request, ResponseInterface $response) {
		var_dump($request->getParams());
		die();
	}
}