<?php
namespace App\ApiControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use App\Models\Utilisateur;

class AuthController extends Controller {

	// inscription d'un utilisateur
	public function postSignUp(RequestInterface $request, ResponseInterface $response) {
		// TODO : à implémenter (voir App\PagesController\AuthController.php)

		$input = $request->getParsedBody();

 		try {
			$validation = v::key('login', v::noWhitespace()->notEmpty()->LoginAvailable())
										 ->key('password', v::notEmpty())
										 ->key('nom', v::notEmpty()->alpha())
										 ->key('prenom', v::notEmpty()->alpha())
										 ->key('mail', v::email()->EmailAvailable())
										 ->key('dateNaissance', v::date())
										 ->key('sexe', v::stringType()->length(1,1))
										 ->assert($input);
		} catch (NestedValidationException $exception) {
			return $response->withJson(['status' => 'Erreur : ' . $exception->getMessages()[0]], 400);
		}
		
		$user = Utilisateur::create([
			"login" => $input['login'],
			"password" => password_hash($input['password'], PASSWORD_DEFAULT),
			"nom" => $input['nom'],
			"prenom" => $input['prenom'],
			"mail" => $input['mail'],
			"dateNaissance" => $input['dateNaissance'],
			"sexe" => $input['sexe']
		]);

		if(!$user) {
			return $response->withJson(['status' => 'Utilisateur non crée'], 400);
		}

		return $response->withJson([
			['status' => 'Utilisateur crée']
		]);
	}

	// connecte un utilisateur
	public function postCreate(RequestInterface $request, ResponseInterface $response) {

		$user = $this->apiauth->attempt(
			$request->getParam('login'),
			$request->getParam('password')
		);

		if(!$user) {
			return $response->withJson(['status' => 'Identifiants incorrects'], 401);
		}

		$token = $this->apiauth->createToken($user);

		return $response->withJson([
			['status' => 'Authentifié avec succès'],
			['token' => $token]
		]);
	}

	// vérifie un token
	public function postCheck(RequestInterface $request, ResponseInterface $response) {
		$token = $request->getParam('token');

		if(!$this->apiauth->checkToken($token)) {
			return $response->withJson(['status' => 'Jeton expiré'], 401);
		}

		return $response->withJson([
			['status' => 'Jeton valide !'],
			['token' => $token]
		]);
	}

	// renouvelle un token
	public function postRenew(RequestInterface $request, ResponseInterface $response) {
		$token = $request->getParam('token');

		$newToken = $this->apiauth->renewToken($token);
		if(!$newToken) {
			return $response->withJson(['status' => 'Jeton invalide'], 401);
		}

		return $response->withJson([
			['status' => 'Jeton renouvelé !'],
			['token' => $newToken]
		]);
	}

	// Retourne des informations sur l'utilisateur connecté
	public function getUser(RequestInterface $request, ResponseInterface $response) {

		// récupère le token depuis l'entête
	 $token = \App\Middleware\ApiAuthMiddleware::fetchToken($request);

	 $user = $this->apiauth->getUser($token);

	 return $response->withJson([
			'id_user' => $user->id_user,
			'login' => $user->login,
			'nom' => $user->nom,
			'prenom' => $user->prenom,
			'mail' => $user->mail,
			'dateNaissance' => $user->dateNaissance,
			'sexe' => $user->sexe
		]);
	}
}
