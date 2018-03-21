<?php
namespace App\PagesControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Models\Utilisateur;

class UserController extends Controller {

	// formulaire d'inscription
	public function getSignup(RequestInterface $request, ResponseInterface $response) {
		$this->render($response, 'pages/signup.twig', []);
	}
	// ajout d'un utilisateur dans la BDD
	public function postSignup(RequestInterface $request, ResponseInterface $response) {
		$input = $request->getParsedBody();

		$user = Utilisateur::create([
			"login" => $input['login'],
			"password" => password_hash($input['password'], PASSWORD_DEFAULT),
			"nom" => $input['nom'],
			"prenom" => $input['prenom'],
			"mail" => $input['mail'],
			"age" => $input['age'],
			"genre" => $input['genre']
		]);

		return $response->withRedirect($this->router->pathFor('home'));
	}

	// formulaire de connexion
	public function getSignin(RequestInterface $request, ResponseInterface $response) {
		$this->render($response, 'pages/signin.twig', []);
	}
	// connecte un Utilisateur
	public function postSignin(RequestInterface $request, ResponseInterface $response) {
		$input = $request->getParsedBody();

		$user = Utilisateur::where('login', $input['login'])->first();

		if(password_verify($input['password'], $user->password)) {
			$access = "GRANTED";
		}
		else { $access = "DENY"; }

		var_dump($access);
	}

	// déconnecte l'utilisateur
	public function getSignout(RequestInterface $request, ResponseInterface $response) {
		$this->render($response, 'pages/signout.twig', []);
	}
}
