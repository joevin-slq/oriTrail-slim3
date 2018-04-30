<?php
namespace App\ApiControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Models\Resultat;

class ResultatController extends Controller {

	// retourne tous les résultats
	public function getAll(RequestInterface $request, ResponseInterface $response) {
		$lieux = Resultat::all();
		return $response->withJson($lieux);
	}

	// retourne un résultat via son identifiant
	public function get(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');
		$lieu = Resultat::where('id_resultat', $id);
		return $response->withJson($lieu->get());
	}

	// enregistre le résultat d'une course
	public function add(RequestInterface $request, ResponseInterface $response) {
		$input = $request->getParsedBody();

		$user = Resultat::create([
			"nom" => $input['nom'],
			"description" => $input['description'],
			"prive" => $input['prive'],
			"type" => $input['type'],
			"debut" => $input['debut'],
			"fin" => $input['fin'],
			"tempsImparti" => $input['tempsImparti'],
			"penalite" => $input['penalite'],
			"fk_user" => $input['user']
		]);

		return $response->withJson($user);
	}

}
