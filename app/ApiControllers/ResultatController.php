<?php
namespace App\ApiControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Models\Resultat;
use App\Models\BaliseResultat;

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

		$resultat = Resultat::create([
			"debut" => $input['debut'],
			"fin" => $input['fin'],
			"score" => $input['score'],
			"fk_user" => $input['user'],
			"fk_course" => $input['course']
		]);

		foreach ($input['balises'] as $balise) {
			$resultat->balisesResultat()->create([
				"tempsInter" => $balise['tempsInter'],
				"fk_baliseCourse" => $balise['baliseCourse'],
			]);
		}

		return $response->withJson($resultat);
	}

}
