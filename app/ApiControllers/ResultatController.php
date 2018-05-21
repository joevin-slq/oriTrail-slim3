<?php
namespace App\ApiControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use App\Models\Resultat;
use App\Models\BaliseResultat;
use App\Models\BaliseCourse;

class ResultatController extends Controller {

	// retourne tous les résultats
	public function getAll(RequestInterface $request, ResponseInterface $response) {
		$lieux = Resultat::all();
		return $response->withJson($lieux);
	}

	// retourne les résultats d'une course
	public function get(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');
		$resultats = Resultat::where('fk_course', $id)->get();
		if(!$resultats->isNotEmpty()) {
			return $response->withJson(['status' => 'Erreur : Résultats de course introuvables.'], 400);
		}
		return $response->withJson($resultats);
	}

	// retourne le run associé au résultat d'une course
	public function getRun(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');
		$resultat = Resultat::where('id_resultat', $id)->first();
		$balises = BaliseResultat::where('fk_resultat', $id)->with('balisesCourse')->get();
		return $response->withJson(array($resultat, $balises));
	}

	// enregistre le résultat d'une course
	public function add(RequestInterface $request, ResponseInterface $response) {
		$input = $request->getParsedBody();
		try {
			$validation = v::key('id_course', v::notEmpty()->numeric())
										 ->key('id_user', v::notEmpty()->numeric())
										 ->key('type', v::stringType()->length(1,1))
										 ->key('debut', v::notEmpty()->date())
										 ->key('fin', v::notEmpty()->date())
							 			 ->key('bals', v::arrayType()->each(
							 				 v::key('num', v::numeric())
											 	->key('temps', v::notEmpty())
							 					->key('longitude', v::notEmpty())
							 					->key('latitude', v::notEmpty())
							 			 ))
							 			 ->assert($input);
		} catch (NestedValidationException $exception) {
			return $response->withJson(['status' => 'Erreur : ' . $exception->getMessages()[0]], 400);
		}

		$donnees = array(
			'fk_course' => $input['id_course'],
			'fk_user' => $input['id_user'],
			'debut' => $input['debut'],
			'fin' => $input['fin']
		);
		if ($input['type'] == 'S') {
			$donnees['score'] = $input['score'];
		}
		$resultat = Resultat::create($donnees);

		// gestion de la balise résultat (associé à la balise de configuration)
		$baliseCourse = BaliseCourse::where('fk_course', $input['id_course'])
																	->where('numero', 0)
																	->first();
		$resultat->balisesResultat()->create([
			'temps' => '00:00:00',
			'fk_baliseCourse' => $baliseCourse->id_baliseCourse
		]);

		// gestion des autres balises (start, cp, fin)
		foreach ($input['bals'] as $baliseResultat) {
			$baliseCourse = BaliseCourse::where('fk_course', $input['id_course'])
																		->where('numero', $baliseResultat['num'])
																		->first();

			$resultat->balisesResultat()->create([
				'temps' => $baliseResultat['temps'],
				'longitude' => $baliseResultat['longitude'],
				'latitude' => $baliseResultat['latitude'],
				'fk_baliseCourse' => $baliseCourse->id_baliseCourse
			]);
		}

		return $response->withJson(
			['status' => 'Résultat de la course de type ' . (($score == 'S') ? 'score' : 'parcours') . ' enregistrée.']
		, 200);
	}
}
