<?php
namespace App\ApiControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;
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
		if(!$resultat) {
			$this->flash->addMessage('error', "Résultat introuvable.");
			return $response->withRedirect($this->router->pathFor('resultat'));
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

		try {
			$validation = v::key('id_course', v::notEmpty()->numeric())
										 ->key('id_user', v::notEmpty()->numeric())
										 ->key('type', v::stringType()->length(1,1))
										 ->key('debut', v::notEmpty()->date())
										 ->key('fin', v::notEmpty()->date())
										 ->assert($request->getParsedBody());
		} catch (NestedValidationException $exception) {
			return $response->withJson(['status' => 'Erreur : ' . $exception->getMessages()[0]], 400);
		}

		if ($request->getParam('type') == "P") {
			$statut = ResultatController::saveParcours($request);
		} else {
			$statut = ResultatController::saveScore($request);
		}

		return $response->withJson($statut[0], $statut[1]);
	}

	// enregistre une course de type parcours
	private function saveParcours(RequestInterface $request) {

		return array([
			['status' => 'Course de type parcours enregistré : TODO']
		], 501);
	}

	// enregistre une course de type score
	private function saveScore(RequestInterface $request) {
		$input = $request->getParsedBody();
		$resultat = Resultat::create([
			'fk_course' => $input['id_course'],
			'fk_user' => $input['id_user'],
			'debut' => $input['debut'],
			'fin' => $input['fin'],
			'score' => $input['score']
		]);

		foreach ($input['balises'] as $baliseResultat) {
			$baliseCourse = BaliseCourse::where('fk_course', $input['id_course'])
																		->where('numero', $baliseResultat['id_baliseCourse'])
																		->first();

			$resultat->balisesResultat()->create([
				'tempsInter' => $baliseResultat['tempsInter'],
				'fk_baliseCourse' => $baliseCourse->id_baliseCourse
			]);
		}

		return array([
			['status' => 'Course de type score enregistré.']
		], 200);
	}
}
