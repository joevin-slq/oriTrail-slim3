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

		return $response->withRedirect($this->router->pathFor('home'));
	}

	// formulaire de connexion
	public function getSignIn(RequestInterface $request, ResponseInterface $response) {
		return $this->render($response, 'pages/signin.twig', []);
	}
	// connecte un Utilisateur
	public function postSignIn(RequestInterface $request, ResponseInterface $response) {
		$input = $request->getParsedBody();

		$user = Utilisateur::where('login', $input['login'])->first();

		if(password_verify($input['password'], $user->password)) {
			$access = "GRANTED";
		}
		else { $access = "DENY"; }

		var_dump($access);
	}

	// déconnecte l'utilisateur
	public function getSignOut(RequestInterface $request, ResponseInterface $response) {
		return $this->render($response, 'pages/signout.twig', []);
	}
}
