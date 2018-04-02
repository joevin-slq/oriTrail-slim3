<?php
use App\Middleware\AuthMiddleware;
use App\Middleware\ApiAuthMiddleware;
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
 * Back-End
 */
// Authentification
$app->post('/api/token/create', \App\ApiControllers\AuthController::class.':postCreate')->setName('token');
$app->post('/api/token/check', \App\ApiControllers\AuthController::class.':postCheck')->setName('check');
$app->post('/api/token/renew', \App\ApiControllers\AuthController::class.':postRenew')->setName('renew');

// ces routes imposent que l'utilisateur soit connecté
$app->group('/api', function() {
  $this->get('/lieux', \App\ApiControllers\LieuController::class.':getAll');
  $this->get('/lieu/[{id}]', \App\ApiControllers\LieuController::class.':get');
  $this->get('/lieu/search/[{query}]', \App\ApiControllers\LieuController::class.':search');
  $this->post('/lieu', \App\ApiControllers\LieuController::class.':add');
  $this->delete('/lieu/[{id}]', \App\ApiControllers\LieuController::class.':delete');
  $this->put('/lieu/[{id}]', \App\ApiControllers\LieuController::class.':update');
})->add(new ApiAuthMiddleware($container));
