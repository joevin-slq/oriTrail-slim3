<?php
namespace App\PagesControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;
use App\Models\Course;
use App\Models\Lieu;

class CourseController extends Controller {

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
		$lieux = Lieu::all();

		$this->render($response, 'pages/course/ajout.twig', [
        'page' => 'course',
				'lieux' => $lieux
    ]);
	}

	public function postAjout(RequestInterface $request, ResponseInterface $response) {

		$validation = $this->validator->validate($request, [
			'nom' => v::notEmpty(),
			'type' => v::stringType()->length(1,1),
			'debut' => v::notEmpty(),
			'fin' => v::notEmpty(),
			'tempsImparti' => v::notEmpty(),
			'penalite' => v::notEmpty(),
			'lieu' => v::notEmpty()
		]);

		if($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('course.ajout'));
		}

		$input = $request->getParsedBody();
		Course::create([
			"nom" => $input['nom'],
			"prive" => ($input['prive']) ? true : false,
			"type" => $input['type'],
			"debut" => date("Y-m-d H:i:s", strtotime($input['debut'])),
			"fin" => date("Y-m-d H:i:s", strtotime($input['fin'])),
			"tempsImparti" => $input['tempsImparti'],
			"penalite" => $input['penalite'],
			"fk_user" => $_SESSION['user'],
			"fk_lieu" => $input['lieu']
		]);

		$this->flash->addMessage('info', 'Course créé avec succès !');

		return $response->withRedirect($this->router->pathFor('course'));
	}

	public function getEdit(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');

		$course = Course::where('id_course', $id)->first();

		$this->render($response, 'pages/course/edit.twig', [
        'page' => 'course',
				'post' => $course,
    ]);
	}

	public function postEdit(RequestInterface $request, ResponseInterface $response) {

		$validation = $this->validator->validate($request, [
			'nom' => v::notEmpty(),
			'type' => v::stringType()->length(1,1),
			'debut' => v::notEmpty(),
			'fin' => v::notEmpty(),
			'tempsImparti' => v::notEmpty(),
			'penalite' => v::notEmpty(),
			'fk_user' => v::notEmpty(),
			'fk_lieu' => v::notEmpty()
		]);

		if($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('course.edit'));
		}

		$input = $request->getParsedBody();
		$id = $request->getAttribute('id');

		Course::where('id_course', $id)->update([
			"nom" => $input['nom'],
			"prive" => ($input['prive']) ? true : false,
			"type" => $input['type'],
			"debut" => date("Y-m-d H:i:s", strtotime($input['debut'])),
			"fin" => date("Y-m-d H:i:s", strtotime($input['fin'])),
			"tempsImparti" => $input['tempsImparti'],
			"penalite" => $input['penalite'],
			"fk_user" => $_SESSION['user'],
			"fk_lieu" => $input['lieu']
		]);

		$this->flash->addMessage('info', 'Course modifié avec succès !');

		return $response->withRedirect($this->router->pathFor('course'));
	}

}
