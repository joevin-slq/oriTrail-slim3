<?php

require '../vendor/autoload.php';

$app = new \Slim\App([
	'settings' => [
		'displayErrorDetails' => true
	]
]);

require('../app/container.php');

$app->get('/', \App\PagesControllers\LieuController::class.':home')->setName('home');

$app->get('/contact', \App\PagesControllers\LieuController::class.':getContact')->setName('contact');

$app->post('/contact', \App\PagesControllers\LieuController::class.':postContact');

$app->get('/lieu', \App\PagesControllers\LieuController::class.':getLieu')->setName('lieu');

// API lieu
$app->get('/api/lieux', \App\ApiControllers\LieuController::class.':getAll');
$app->get('/api/lieu/[{id}]', \App\ApiControllers\LieuController::class.':get');
$app->get('/api/lieu/search/[{query}]', \App\ApiControllers\LieuController::class.':search');
$app->post('/api/lieu', \App\ApiControllers\LieuController::class.':add');
$app->delete('/api/lieu/[{id}]', \App\ApiControllers\LieuController::class.':delete');
$app->put('/api/lieu/[{id}]', \App\ApiControllers\LieuController::class.':update');

$app->run();