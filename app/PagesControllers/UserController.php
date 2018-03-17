<?php
namespace App\PagesControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController extends Controller {

	// formulaire d'inscription
	public function getSignup(RequestInterface $request, ResponseInterface $response) {
		$this->render($response, 'pages/signup.twig', []);
	}
	// ajout d'un utilisateur dans la BDD
	public function postSignup(RequestInterface $request, ResponseInterface $response) {
		$input = $request->getParsedBody();

		$req = $this->pdo->prepare('INSERT INTO Utilisateur
			   (login, password, nom, prenom, mail, age, genre)
		VALUES (:login, :password, :nom, :prenom, :mail, :age, :genre)
		');
        $req->execute(array(
          "login" => $input['login'],
          "password" => password_hash($input['password'], PASSWORD_DEFAULT), 
          "nom" => $input['nom'], 
          "prenom" => $input['prenom'],
          "mail" => $input['mail'],
          "age" => $input['age'],
          "genre" => $input['genre']
        ));
		$req->execute();

		$input['id'] = $this->pdo->lastInsertId();

		var_dump($input);
	}

	// formulaire de connexion
	public function getSignin(RequestInterface $request, ResponseInterface $response) {
		$this->render($response, 'pages/signin.twig', []);
	}
	// connecte un utilisateur
	public function postSignin(RequestInterface $request, ResponseInterface $response) {
		$input = $request->getParsedBody();

		$req = $this->pdo->prepare('SELECT login, password
									FROM Utilisateur
									WHERE login = ?');
        $req->execute(array($input['login']));
		$result = $req->fetch();

		if(password_verify($input['password'], $result['password'])) {
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