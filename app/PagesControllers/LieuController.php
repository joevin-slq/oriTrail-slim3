<?php
namespace App\PagesControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;
use App\Models\Lieu;

class LieuController extends Controller {

	public function getAll(RequestInterface $request, ResponseInterface $response) {
		$lieux = Lieu::all();

		$this->render($response, 'pages/lieu/lieu.twig', [
        	'lieux' => $lieux
		]);
	}

	public function getSuppr(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');

		Lieu::where('id_lieu', $id)->delete();

		$this->flash->addMessage('info', 'Lieu supprimé avec succès !');

		return $response->withRedirect($this->router->pathFor('lieu'));
	}


	public function getAjout(RequestInterface $request, ResponseInterface $response) {
		$this->render($response, 'pages/lieu/ajout.twig', [
        'page' => 'lieu'
    ]);
	}

	public function postAjout(RequestInterface $request, ResponseInterface $response) {

		$validation = $this->validator->validate($request, [
			'nom' => v::notEmpty(),
			'description' => v::notEmpty(),
			'longitude' => v::numeric(),
			'latitude' => v::numeric(),
			'adresse' => v::notEmpty(),
			'cp' => v::stringType()->length(5,5),
			'ville' => v::notEmpty(),
		]);

		if($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('lieu.ajout'));
		}

		$input = $request->getParsedBody();
		Lieu::create([
			"nom" => $input['nom'],
			"description" => $input['description'],
			"longitude" => $input['longitude'],
			"latitude" => $input['latitude'],
			"adresse" => $input['adresse'],
			"cp" => $input['cp'],
			"ville" => $input['ville']
		]);

		$this->flash->addMessage('info', 'Lieu créé avec succès !');

		return $response->withRedirect($this->router->pathFor('lieu'));
	}

	public function getEdit(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');

		$lieu = Lieu::where('id_lieu', $id)->first();

		$this->render($response, 'pages/lieu/edit.twig', [
        'page' => 'lieu',
				'post' => $lieu,
    ]);
	}

	public function postEdit(RequestInterface $request, ResponseInterface $response) {

		$validation = $this->validator->validate($request, [
			'nom' => v::notEmpty(),
			'description' => v::notEmpty(),
			'longitude' => v::numeric(),
			'latitude' => v::numeric(),
			'adresse' => v::notEmpty(),
			'cp' => v::stringType()->length(5,5),
			'ville' => v::notEmpty(),
		]);

		if($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('lieu.edit'));
		}

		$input = $request->getParsedBody();
		$id = $request->getAttribute('id');

		Lieu::where('id_lieu', $id)->update([
			"nom" => $input['nom'],
			"description" => $input['description'],
			"longitude" => $input['longitude'],
			"latitude" => $input['latitude'],
			"adresse" => $input['adresse'],
			"cp" => $input['cp'],
			"ville" => $input['ville']
		]);

		$this->flash->addMessage('info', 'Lieu modifié avec succès !');

		return $response->withRedirect($this->router->pathFor('lieu'));
	}

}
