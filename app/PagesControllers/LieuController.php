<?php
namespace App\PagesControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LieuController extends Controller {

	public function getLieu(RequestInterface $request, ResponseInterface $response) {

		$req = $this->pdo->prepare('SELECT * FROM Lieu');
	    $req->execute();
		$lieux =  $req->fetchAll();

		$this->render($response, 'pages/lieu.twig', [
        	'lieux' => $lieux
		]);
	}
}