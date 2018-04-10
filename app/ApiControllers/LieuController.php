<?php
namespace App\ApiControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Models\Lieu;

class LieuController extends Controller {

	// récupère tous les lieux
	public function getAll(RequestInterface $request, ResponseInterface $response) {
		$lieux = Lieu::all();
		return $response->withJson($lieux);
	}

	// récupère un lieu via son identifiant
	public function get(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');
		$lieu = Lieu::where('id_lieu', $id);
		return $response->withJson($lieu->get());
	}

 	// recherche un lieu
	public function search(RequestInterface $request, ResponseInterface $response) {
		$query = '%' . $request->getAttribute('query') . '%';
		$lieu = Lieu::where('nom', 'LIKE', $query);
		return $response->withJson($lieu->get());
	}

	// ajoute un lieu
	public function add(RequestInterface $request, ResponseInterface $response) {
		$input = $request->getParsedBody();

		$user = Lieu::create([
			"nom" => $input['nom'],
			"description" => $input['description'],
			"longitude" => $input['longitude'],
			"latitude" => $input['latitude'],
			"adresse" => $input['adresse'],
			"cp" => $input['cp'],
			"ville" => $input['ville']
		]);

		return $response->withJson($user);
	}

	// supprime un lieu via son identifiant
	public function delete(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');
		$lieu = Lieu::where('id_lieu', $id);
		if(!$lieu->delete()) {
			return $response->withJson([
				['status' => 'Lieu introuvable']
			],404);
		}

		return $response->withJson([
			['status' => $lieu->nom . 'Lieu supprimé avec succès']
		]);
	}


	// met à jour un lieu via son identifiant
	public function update(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');
		$input = $request->getParsedBody();

		$lieu = Lieu::where('id_lieu', $id)->update([
			"nom" => $input['nom'],
			"description" => $input['description'],
			"longitude" => $input['longitude'],
			"latitude" => $input['latitude'],
			"adresse" => $input['adresse'],
			"cp" => $input['cp'],
			"ville" => $input['ville']
		]);

		if(!$lieu) {
			return $response->withJson([
				['status' => 'Erreur']
			],400);
		}

		return $response->withJson([
			['status' => $lieu->nom . 'Lieu modifié avec succès']
		]);
	}
}
