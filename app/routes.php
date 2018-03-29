<?php
use App\Middleware\AuthMiddleware;
/*
 * Front-End
 */
// Page d'accueil
$app->get('/', \App\PagesControllers\LieuController::class.':home')->setName('home');

// Authentification
$app->get('/auth/signup', \App\PagesControllers\AuthController::class.':getSignUp')->setName('signup');
$app->post('/auth/signup', \App\PagesControllers\AuthController::class.':postSignUp')->setName('signup');

$app->get('/auth/signin', \App\PagesControllers\AuthController::class.':getSignIn')->setName('signin');
$app->post('/auth/signin', \App\PagesControllers\AuthController::class.':postSignIn')->setName('signin');

// ces routes imposent que l'utilisateur soit connecté
$app->group('', function() {

  $this->get('/lieu', \App\PagesControllers\LieuController::class.':getLieu')->setName('lieu');

  $this->get('/lieu/ajout', \App\PagesControllers\LieuController::class.':getAjout')->setName('lieu.ajout');
  $this->post('/lieu/ajout', \App\PagesControllers\LieuController::class.':postAjout')->setName('lieu.ajout');

  $this->get('/lieu/suppr/[{id}]', \App\PagesControllers\LieuController::class.':getSuppr')->setName('lieu');

  $this->get('/auth/signout', \App\PagesControllers\AuthController::class.':getSignOut')->setName('signout');
})->add(new AuthMiddleware($container));


/*
 * API lieu
 */
$app->get('/api/lieux', \App\ApiControllers\LieuController::class.':getAll');
$app->get('/api/lieu/[{id}]', \App\ApiControllers\LieuController::class.':get');
$app->get('/api/lieu/search/[{query}]', \App\ApiControllers\LieuController::class.':search');
$app->post('/api/lieu', \App\ApiControllers\LieuController::class.':add');
$app->delete('/api/lieu/[{id}]', \App\ApiControllers\LieuController::class.':delete');
$app->put('/api/lieu/[{id}]', \App\ApiControllers\LieuController::class.':update');
