<?php
namespace App\PagesControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LieuController extends Controller {

	public function home(RequestInterface $request, ResponseInterface $response) {
		$this->render($response, 'pages/home.twig', []);
	}

	public function getContact(RequestInterface $request, ResponseInterface $response) {
		$this->render($response, 'pages/contact.twig', []);
	}

	public function postContact(RequestInterface $request, ResponseInterface $response) {
		var_dump($request->getParams());
		die();
	}

	public function getLieu(RequestInterface $request, ResponseInterface $response) {

		$req = $this->pdo->prepare('SELECT * FROM Lieu');
	    $req->execute();
		$lieux =  $req->fetchAll();

		$this->render($response, 'pages/lieu.twig', [
        	'lieux' => $lieux
		]);
	}
}