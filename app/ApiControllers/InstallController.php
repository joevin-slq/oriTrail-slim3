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
			 ->key('id_user', v::notEmpty())
			 ->key('bals', v::arrayType()->each(
				 v::key('num', v::numeric())
					->key('longitude', v::notEmpty())
					->key('latitude', v::notEmpty())
			 ))
			 ->assert($input);
		} catch (NestedValidationException $exception) {
			return $response->withJson(['status' => 'Erreur : ' . $exception->getMessages()[0]], 400);
		}

		// vérifie que l'utilisateur qui installe la course est l'organisateur
		$course = Course::where('id_course', $input['id_course'])
									  ->where('fk_user', $input['id_user'])
								    ->first();
		if(!$course) {
			return $response->withJson(
				['status' => 'Installation non autorisée !']
			, 401);
		}

		foreach ($input['bals'] as $balise) {
			// on ne tiens pas compte de la position de la balise de configuration
			if($balise['num'] == 0) { continue; }

			BaliseCourse::where('fk_course', $input['id_course'])
									->where('numero', $balise['num'])
									->update([
				'longitude' => $balise['longitude'],
				'latitude'  => $balise['latitude']
			]);
		}

		return $response->withJson(
			['status' => 'Course installée avec succès']
		);
	}


}
