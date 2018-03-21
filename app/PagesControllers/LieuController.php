<?php
namespace App\PagesControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Models\Lieu;

class LieuController extends Controller {

	public function getLieu(RequestInterface $request, ResponseInterface $response) {
		$lieux = Lieu::all();

		$this->render($response, 'pages/lieu.twig', [
        	'lieux' => $lieux
		]);
	}

}
