<?php
namespace App\PagesControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;
use App\Models\Course;
use App\Models\Resultat;
use App\Models\BaliseResultat;

class ResultatController extends Controller {

	/**
	 * Affiche l'ensemble des courses
	 */
	public function getCourse(RequestInterface $request, ResponseInterface $response) {
		$courses = Course::where('prive', false)
											 ->orWhere('fk_user', $this->auth->user()->id_user)
											 ->get();

		$this->render($response, 'pages/resultat/course.twig', [
  			'courses' => $courses
		]);
	}

	/**
	 * Affiche les résultats d'une course
	 */
	public function getResultat(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');

		$resultats = Resultat::where('fk_course', $id)->get();

		$course = Course::where('id_course', $id)->first();
		$modeScore = ($course->type == 'S') ? true : false;

		$this->render($response, 'pages/resultat/resultat.twig', [
				'resultats' => $resultats,
				'course' => $course,
				'modeScore' => $modeScore
    ]);
	}

	/**
	 * Affiche le détail d'un résultat de course
	 */
	public function getRun(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');

		$resultat = Resultat::where('id_resultat', $id)->first();
		if(!$resultat) {
			$this->flash->addMessage('error', "Résultat introuvable.");
			return $response->withRedirect($this->router->pathFor('resultat'));
		}

		$this->render($response, 'pages/resultat/run.twig', [
				'resultat' => $resultat
    ]);
	}
}
