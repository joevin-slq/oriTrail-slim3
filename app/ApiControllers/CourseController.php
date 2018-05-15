<?php
namespace App\ApiControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use App\Models\Course;

class CourseController extends Controller {

	// récupère tous les courses
	public function getAll(RequestInterface $request, ResponseInterface $response) {
		$courses = Course::all();
		return $response->withJson($courses);
	}

	// récupère une course via son identifiant
	public function get(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');
		$course = Course::where('id_course', $id)->first();
		if(!$course) {
			return $response->withJson(['status' => 'Erreur : course introuvable'], 404);
		}
		return $response->withJson($course);
	}

 	// recherche une course
	public function search(RequestInterface $request, ResponseInterface $response) {
		$query = '%' . $request->getAttribute('query') . '%';
		$course = Course::where('nom', 'LIKE', $query)->get();
		if($course->count() == 0) {
			return $response->withJson(['status' => 'Erreur : course introuvable'], 404);
		}
		return $response->withJson($course);
	}

	// ajoute une course
	public function add(RequestInterface $request, ResponseInterface $response) {
		$input = $request->getParsedBody();

		try {
			$validation = v::key('nom', v::notEmpty()->stringType())
										 ->key('description', v::stringType())
										 ->key('type', v::stringType()->length(1,1))
										 ->key('debut', v::date())
										 ->key('fin', v::date())
										 ->assert($input);
		} catch (NestedValidationException $exception) {
			return $response->withJson(['status' => 'Erreur : ' . $exception->getMessages()[0]], 400);
		}

		$course = Course::create([
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

		if(!$course) {
			return $response->withJson(['status' => 'Erreur'],400);
		}

		return $response->withJson(
			['status' => 'Course "' . $course->nom . '" créée avec succès']
		);
	}

	// supprime une course via son identifiant
	public function delete(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');
		$course = Course::where('id_course', $id);
		if(!$course->delete()) {
			return $response->withJson([
				['status' => 'Course introuvable']
			], 404);
		}

		return $response->withJson(
			['status' => $course->nom . 'Course supprimé avec succès']
		);
	}
}
