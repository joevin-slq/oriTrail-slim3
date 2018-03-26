<?php
namespace App\PagesControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;
use App\Models\Utilisateur;

class AuthController extends Controller {

	// formulaire d'inscription
	public function getSignUp(RequestInterface $request, ResponseInterface $response) {
		return $this->render($response, 'pages/signup.twig', []);
	}
	// ajout d'un utilisateur dans la BDD
	public function postSignUp(RequestInterface $request, ResponseInterface $response) {

		$validation = $this->validator->validate($request, [
			'login' => v::noWhitespace()->notEmpty()->LoginAvailable(),
			'password' => v::notEmpty(),
			'nom' => v::notEmpty()->alpha(),
			'prenom' => v::notEmpty()->alpha(),
			'mail' => v::email()->EmailAvailable(),
			'dateNaissance' => v::notEmpty(),
			'sexe' => v::noWhitespace()->notEmpty(),
		]);

		if($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('signup'));
		}

		$input = $request->getParsedBody();
		$user = Utilisateur::create([
			"login" => $input['login'],
			"password" => password_hash($input['password'], PASSWORD_DEFAULT),
			"nom" => $input['nom'],
			"prenom" => $input['prenom'],
			"mail" => $input['mail'],
			"dateNaissance" => $input['dateNaissance'],
			"sexe" => $input['sexe']
		]);

		$this->auth->attempt($user->login, $input['password']);

		return $response->withRedirect($this->router->pathFor('home'));
	}

	// formulaire de connexion
	public function getSignIn(RequestInterface $request, ResponseInterface $response) {
		return $this->render($response, 'pages/signin.twig', []);
	}

	// connecte un Utilisateur
	public function postSignIn(RequestInterface $request, ResponseInterface $response) {
		$auth = $this->auth->attempt(
			$request->getParam('login'),
			$request->getParam('password')
		);

		if(!$auth) {
			return $response->withRedirect($this->router->pathFor('signin'));
		}
		return $response->withRedirect($this->router->pathFor('home'));
	}

	// déconnecte l'utilisateur
	public function getSignOut(RequestInterface $request, ResponseInterface $response) {
		$this->auth->logout();
		
		return $response->withRedirect($this->router->pathFor('home'));
	}
}
