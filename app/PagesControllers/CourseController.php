<?php
namespace App\PagesControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;
use App\Models\Course;
use App\Models\BaliseCourse;
use App\PagesControllers\BaliseController;

class CourseController extends Controller {

	/**
	 * Affiche une course à partir de son identifiant
	 */
	public function get(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');

		$course = Course::where('id_course', $id)
										  ->where('fk_user', $this->auth->user()->id_user)
											->first();
		if(!$course) {
			$this->flash->addMessage('error', "Impossible d'accéder à cette course.");
			return $response->withRedirect($this->router->pathFor('course'));
		}

		if(!$course->estInstalle()) {
			$this->flash->addMessageNow('warn', "Attention : La course n'est pas installée, les positions GPS des balises ne sont pas renseignées.");
		}

		$this->render($response, 'pages/course/get.twig', [
        'page' => 'course',
				'course' => $course,
				'balises' => $course->balisesCourse
    ]);
	}

	/**
	 * Affiche l'intégralité des courses
	 */
	public function getAll(RequestInterface $request, ResponseInterface $response) {
		$courses = Course::where('fk_user', $this->auth->user()->id_user)->get();

		$this->render($response, 'pages/course/course.twig', [
  			'courses' => $courses
		]);
	}

	/**
	 * Supprime une course à partir de son identifiant
	 */
	public function getSuppr(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');

		$retour = Course::where('id_course', $id)
						->where('fk_user', $this->auth->user()->id_user)
						->delete();

		if(!$retour) {
			$this->flash->addMessage('error', 'Impossible de supprimer la course !');
		} else {
			$this->flash->addMessage('info', 'Course supprimé avec succès !');
		}

		return $response->withRedirect($this->router->pathFor('course'));
	}

	/**
	 * Affiche la page qui permet de créer une course
	 */
	public function getAjout(RequestInterface $request, ResponseInterface $response) {
		$this->render($response, 'pages/course/ajout.twig', [
        'page' => 'course'
    ]);
	}

	/**
	 * Crée une nouvelle course composé de plusieurs balises
	 */
	public function postAjout(RequestInterface $request, ResponseInterface $response) {

		$validation = $this->validator->validate($request, [
			'nom' => v::notEmpty()->stringType(),
			'description' => v::stringType(),
			'type' => v::stringType()->length(1,1),
			'debut' => v::date('d/m/Y H:i'),
			'fin' => v::date('d/m/Y H:i'),
			// 'tempsImparti' => v::date('H:i'),
			// 'penalite' => v::date('H:i:s'),
			'nomBalise' => v::notEmpty(),
			'valeurBalise' => v::notEmpty()
		]);

		if($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('course.ajout'));
		}

		$input = $request->getParsedBody();
		$course = Course::create([
			"nom" => $input['nom'],
			"description" => $input['description'],
			"prive" => (isset($input['prive'])) ? true : false,
			"type" => $input['type'],
			"debut" => date_format(date_create_from_format('d/m/Y H:i', $input['debut']), 'Y-m-d  H:i:s'),
			"fin" => date_format(date_create_from_format('d/m/Y H:i', $input['fin']), 'Y-m-d  H:i:s'),
			"tempsImparti" => $input['tempsImparti'],
			"penalite" => $input['penalite'],
			"fk_user" => $_SESSION['user']
		]);

		BaliseController::createBalises($course, $input['nomBalise'], $input['valeurBalise']);

		$this->flash->addMessage('info', 'Course créé avec succès !');

		return $response->withRedirect($this->router->pathFor('course'));
	}

	/**
	 * Récupère les informations de la course à dupliquer pour en créer une nouvelle
	 */
	public function getDuplicate(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');

		$course = Course::where('id_course', $id)->first();

		// Transforme la datetime en date francaise
		$course->debut = date_format(date_create_from_format('Y-m-d  H:i:s',  $course->debut), 'd/m/Y H:i');
		$course->fin = date_format(date_create_from_format('Y-m-d  H:i:s',  $course->fin), 'd/m/Y H:i');

		// Supprime les balises configuration, start et stop
		$nbBalise = count($course->balisesCourse);

		// La balise stop en mode score n'existe pas
		$aBaliseStop = ($course->type == ('S')) ? 0 : 1;

		$nomBalise = Array();
		$valeurBalise = Array();

		if ($nbBalise != 2 + $aBaliseStop) {
			for($i=0, $j=2; $j < $nbBalise - $aBaliseStop; $i++, $j++) {
				$nomBalise[$i] = $course->balisesCourse[$j]->nom;
				$valeurBalise[$i] = $course->balisesCourse[$j]->valeur;
			}
		}

		$this->render($response, 'pages/course/ajout.twig', [
        'page' => 'course',
				'old' => $course,
				'nomBalise' => $nomBalise,
				'valeurBalise' => $valeurBalise,
    ]);
	}

}
