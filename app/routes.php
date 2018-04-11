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
  $this->get('/lieu', \App\PagesControllers\LieuController::class.':getAll')->setName('lieu');

  $this->get('/lieu/ajout', \App\PagesControllers\LieuController::class.':getAjout')->setName('lieu.ajout');
  $this->post('/lieu/ajout', \App\PagesControllers\LieuController::class.':postAjout')->setName('lieu.ajout');

  $this->get('/lieu/edit/[{id}]', \App\PagesControllers\LieuController::class.':getEdit')->setName('lieu.edit');
  $this->post('/lieu/edit/[{id}]', \App\PagesControllers\LieuController::class.':postEdit')->setName('lieu.edit');

  $this->get('/lieu/suppr/[{id}]', \App\PagesControllers\LieuController::class.':getSuppr')->setName('lieu');


  $this->get('/course', \App\PagesControllers\CourseController::class.':getAll')->setName('course');

  $this->get('/course/ajout', \App\PagesControllers\CourseController::class.':getAjout')->setName('course.ajout');
  $this->post('/course/ajout', \App\PagesControllers\CourseController::class.':postAjout')->setName('course.ajout');

  $this->get('/course/edit/[{id}]', \App\PagesControllers\CourseController::class.':getEdit')->setName('course.edit');
  $this->post('/course/edit/[{id}]', \App\PagesControllers\CourseController::class.':postEdit')->setName('course.edit');

  $this->get('/course/suppr/[{id}]', \App\PagesControllers\CourseController::class.':getSuppr')->setName('course');



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
