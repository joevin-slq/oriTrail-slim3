<?php
namespace App\ApiControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Models\Course;

class CourseController extends Controller {

	// récupère tous les courses
	public function getAll(RequestInterface $request, ResponseInterface $response) {
		$lieux = Course::all();
		return $response->withJson($lieux);
	}

	// récupère une course via son identifiant
	public function get(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');
		$lieu = Course::where('id_course', $id);
		return $response->withJson($lieu->get());
	}

 	// recherche une course
	public function search(RequestInterface $request, ResponseInterface $response) {
		$query = '%' . $request->getAttribute('query') . '%';
		$lieu = Course::where('nom', 'LIKE', $query);
		return $response->withJson($lieu->get());
	}

	// ajoute une course
	public function add(RequestInterface $request, ResponseInterface $response) {
		$input = $request->getParsedBody();

		$user = Course::create([
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

	// supprime une course via son identifiant
	public function delete(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');
		$lieu = Course::where('id_course', $id);
		if(!$lieu->delete()) {
			return $response->withJson([
				['status' => 'Course introuvable']
			],404);
		}

		return $response->withJson([
			['status' => $lieu->nom . 'Course supprimé avec succès']
		]);
	}

	// met à jour un lieu via son identifiant
	public function update(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');
		$input = $request->getParsedBody();

		$lieu = Course::where('id_course', $id)->update([
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

		if(!$lieu) {
			return $response->withJson([
				['status' => 'Erreur']
			],400);
		}

		return $response->withJson([
			['status' => $lieu->nom . 'Course modifié avec succès']
		]);
	}
}
