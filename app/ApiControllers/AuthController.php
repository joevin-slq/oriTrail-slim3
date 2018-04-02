<?php
namespace App\ApiControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthController extends Controller {

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

}
