<?php

require '../vendor/autoload.php';

$app = new \Slim\App([
	'settings' => [
		'displayErrorDetails' => true
	]
]);

class Database{
	private $pdo;

	public function __construct(PDO $pdo) {
		$this->pdo = $pdo;
	}

	public function query($sql) {
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}
}

require('../app/container.php');

$app->get('/', \App\Controllers\PagesController::class.':home')->setName('home');

$app->get('/contact', \App\Controllers\PagesController::class.':getContact')->setName('contact');

$app->post('/contact', \App\Controllers\PagesController::class.':postContact');

$app->get('/lieu', \App\Controllers\PagesController::class.':getLieu')->setName('lieu');

$app->get('/lieuBis', function (\Slim\Http\Request $request, \Slim\Http\Response $response) {
	// this works here :
	//$lieux = $this->db->query('SELECT * FROM Lieu');

	// or this is working too :
	$mapper = new Database($this->pdo);
   	$lieux = $mapper->query('SELECT * FROM Lieu');

	var_dump($lieux);

	return $response->write('Salut');
});


$app->run();