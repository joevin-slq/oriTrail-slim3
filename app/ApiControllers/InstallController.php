<?php
namespace App\ApiControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use App\Models\Course;
use App\Models\BaliseCourse;

class InstallController extends Controller {

	/**
	 * Permet d'ajouter les positions GPS aux balises d'une course
	 */
	public function set(RequestInterface $request, ResponseInterface $response) {
		$input = $request->getParsedBody();

		try {
			$validation =
			v::key('id_course', v::notEmpty())
		 	 ->key('balises',
			    v::key('numero', v::notEmpty())
					 ->key('longitude', v::notEmpty())
					 ->key('latitude', v::notEmpty())
			 )->assert($input);
		} catch (NestedValidationException $exception) {
			return $response->withJson(['status' => 'Erreur : ' . $exception->getMessages()[0]], 400);
		}

		/* TODO : Marquer la course comme installée
		$course = Course::where('id_course', $input['course'])->update([
			"instal" => true
		]); */

		return $response->withJson(
			['status' => 'Course "' . $course->nom . '" créée avec succès']
		);
	}


}
