<?php
namespace App\ApiControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LieuController extends Controller {

	// récupère tous les lieux
	public function getAll(RequestInterface $request, ResponseInterface $response) {
		$req = $this->pdo->query('SELECT * FROM Lieu');

		return $response->withJson($req->fetchAll());
	}

	// récupère un lieu via son identifiant
	public function get(RequestInterface $request, ResponseInterface $response) {
		$req = $this->pdo->prepare('SELECT * FROM Lieu WHERE id_lieu = ?');
		$req->execute([$request->getAttribute('id')]);
		$lieu = $req->fetch();

		return $response->withJson($lieu);
	}

 	// recherche un lieu
	public function search(RequestInterface $request, ResponseInterface $response) {
		$req = $this->pdo->prepare('SELECT * FROM Lieu WHERE nom LIKE ?');
		$req->execute(['%' . $request->getAttribute('query') . '%']);
		$lieu = $req->fetchAll();

		return $response->withJson($lieu);
	}

	// ajoute un lieu
	public function add(RequestInterface $request, ResponseInterface $response) {
		$input = $request->getParsedBody();

		$req = $this->pdo->prepare('INSERT INTO Client
			   (nom, description, longitude, latitude, adresse, cp, ville)
		VALUES (:nom, :description, :longitude, :latitude, :adresse, :cp, :ville)
		');
        $req->execute(array(
          "nom" => $input['nom'],
          "description" => $input['description'], 
          "longitude" => $input['longitude'], 
          "latitude" => $input['latitude'],
          "adresse" => $input['adresse'],
          "cp" => $input['cp'],
          "ville" => $input['ville']
        ));
		$req->execute();

		$input['id'] = $this->pdo->lastInsertId();
	    return $this->response->withJson($input);
	}


	// supprime un lieu via son identifiant
	public function delete(RequestInterface $request, ResponseInterface $response) {
		$req = $this->pdo->prepare('DELETE FROM Lieu WHERE id_lieu = ?');
		$req->execute([$request->getAttribute('id')]);
		$lieu = $req->fetchAll();

		return $response->withJson($lieu);
	}


	// met à jour un lieu via son identifiant
	public function update(RequestInterface $request, ResponseInterface $response) {
		$input = $request->getParsedBody();

		$req = $this->pdo->prepare('UPDATE Chambre SET
		  nom = :nom, 
		  description = :description,
		  longitude = :longitude,
		  latitude = :latitude,
		  adresse = :adresse,
		  cp = :cp,
		  ville = :ville
		  WHERE id_chambre = :id
		');

        $req->execute(array(
          "nom" => $input['nom'], 
          "description" => $input['description'], 
          "longitude" => $input['longitude'], 
          "latitude" => $input['latitude'],
          "adresse" => $input['adresse'],
          "cp" => $input['cp'],
          "ville" => $input['ville'],
          "id" => $request->getAttribute('id')
        ));
		$req->execute();

		$input['id'] = $request->getAttribute('id');
	    return $this->response->withJson($input);
	}
}