<?php
namespace App\PagesControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;
use App\Models\Course;
use App\Models\BaliseCourse;
use App\PagesControllers\BaliseController;

class CourseController extends Controller {

	public function get(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');

		$course = Course::where('id_course', $id)->first();

		$this->render($response, 'pages/course/get.twig', [
        'page' => 'course',
				'course' => $course,
				'balises' => $course->balisesCourse
    ]);
	}

	public function getAll(RequestInterface $request, ResponseInterface $response) {
		$courses = Course::all();

		$this->render($response, 'pages/course/course.twig', [
  			'courses' => $courses
		]);
	}

	public function getSuppr(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');

		Course::where('id_course', $id)->delete();

		$this->flash->addMessage('info', 'Course supprimé avec succès !');

		return $response->withRedirect($this->router->pathFor('course'));
	}


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
			'tempsImparti' => v::date('H:i'),
			'penalite' => v::date('H:i:s'),
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
			"debut" => date("Y-m-d H:i:s", strtotime($input['debut'])),
			"fin" => date("Y-m-d H:i:s", strtotime($input['fin'])),
			"tempsImparti" => $input['tempsImparti'],
			"penalite" => $input['penalite'],
			"fk_user" => $_SESSION['user']
		]);

		BaliseController::createBalises($course, $input['nomBalise'], $input['valeurBalise']);

		$this->flash->addMessage('info', 'Course créé avec succès !');

		return $response->withRedirect($this->router->pathFor('course'));
	}

	public function getEdit(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');

		$course = Course::where('id_course', $id)->first();

		$this->render($response, 'pages/course/edit.twig', [
        'page' => 'course',
				'course' => $course,
				'balises' => $course->balisesCourse
    ]);
	}

	public function postEdit(RequestInterface $request, ResponseInterface $response) {

		$validation = $this->validator->validate($request, [
			'nom' => v::notEmpty()->alpha(),
			'description' => v::stringType(),
			'type' => v::stringType()->length(1,1),
			'debut' => v::date('d/m/Y H:i'),
			'fin' => v::date('d/m/Y H:i'),
			'tempsImparti' => v::date('H:i'),
			'penalite' => v::date('H:i:s'),
			'lieu' => v::notEmpty()->numeric()
		]);

		if($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('course.edit'));
		}

		$input = $request->getParsedBody();
		$id = $request->getAttribute('id');

		Course::where('id_course', $id)->update([
			"nom" => $input['nom'],
			"description" => $input['description'],
			"prive" => ($input['prive']) ? true : false,
			"type" => $input['type'],
			"debut" => date("Y-m-d H:i:s", strtotime($input['debut'])),
			"fin" => date("Y-m-d H:i:s", strtotime($input['fin'])),
			"tempsImparti" => $input['tempsImparti'],
			"penalite" => $input['penalite'],
			"fk_user" => $_SESSION['user']
		]);

		$this->flash->addMessage('info', 'Course modifié avec succès !');

		return $response->withRedirect($this->router->pathFor('course'));
	}

}
