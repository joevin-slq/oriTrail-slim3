<?php
use App\Middleware\AuthMiddleware;
use App\Middleware\ApiAuthMiddleware;
/*
 * Front-End
 */
// Page d'accueil
$app->get('/', \App\PagesControllers\Controller::class.':home')->setName('home');

// Authentification
$app->get('/auth/signup', \App\PagesControllers\AuthController::class.':getSignUp')->setName('signup');
$app->post('/auth/signup', \App\PagesControllers\AuthController::class.':postSignUp')->setName('signup');

$app->get('/auth/signin', \App\PagesControllers\AuthController::class.':getSignIn')->setName('signin');
$app->post('/auth/signin', \App\PagesControllers\AuthController::class.':postSignIn')->setName('signin');

// ces routes imposent que l'utilisateur soit connecté
$app->group('', function() {
	
  // Page d'aide
  $this->get('/aide', \App\PagesControllers\Controller::class.':aide')->setName('aide');
	
  $this->get('/course', \App\PagesControllers\CourseController::class.':getAll')->setName('course');

  $this->get('/course/ajout', \App\PagesControllers\CourseController::class.':getAjout')->setName('course.ajout');
  $this->post('/course/ajout', \App\PagesControllers\CourseController::class.':postAjout')->setName('course.ajout');

  $this->get('/course/ajout/[{id}]', \App\PagesControllers\CourseController::class.':getDuplicate')->setName('course.ajout.duplicate');
  $this->post('/course/ajout/[{id}]', \App\PagesControllers\CourseController::class.':postDuplicate')->setName('course.ajout.duplicate');

  $this->get('/course/suppr/[{id}]', \App\PagesControllers\CourseController::class.':getSuppr')->setName('course');

  $this->get('/course/[{id}]', \App\PagesControllers\CourseController::class.':get')->setName('course.get');

  $this->get('/course/pdfQrCode/[{id}]', \App\PagesControllers\CourseController::class.':pdfQrCode')->setName('course.pdfQrCode');

  $this->get('/resultat', \App\PagesControllers\ResultatController::class.':getCourse')->setName('resultat');
  $this->get('/resultat/[{id}]', \App\PagesControllers\ResultatController::class.':getResultat')->setName('resultat.get');
  $this->get('/resultat/run/[{id}]', \App\PagesControllers\ResultatController::class.':getRun')->setName('resultat.run');

  $this->get('/auth/signout', \App\PagesControllers\AuthController::class.':getSignOut')->setName('signout');
})->add(new AuthMiddleware($container));


/*
 * Back-End
 */
// Authentification
$app->post('/api/token/create', \App\ApiControllers\AuthController::class.':postCreate')->setName('token');
$app->post('/api/token/check', \App\ApiControllers\AuthController::class.':postCheck')->setName('check');
// $app->post('/api/token/renew', \App\ApiControllers\AuthController::class.':postRenew')->setName('renew');

// Inscription
$app->post('/api/signup', \App\ApiControllers\AuthController::class.':postSignUp')->setName('signup');

// ces routes imposent que l'utilisateur soit connecté
$app->group('/api', function() {
  $this->get('/user', \App\ApiControllers\AuthController::class.':getUser')->setName('user');

  $this->get('/course', \App\ApiControllers\CourseController::class.':getAll');
  $this->get('/course/[{id}]', \App\ApiControllers\CourseController::class.':get');
  $this->get('/course/search/[{query}]', \App\ApiControllers\CourseController::class.':search');
  $this->post('/course', \App\ApiControllers\CourseController::class.':add');
  $this->delete('/course/[{id}]', \App\ApiControllers\CourseController::class.':delete');

  $this->post('/install', \App\ApiControllers\InstallController::class.':set');

  $this->get('/resultat', \App\ApiControllers\ResultatController::class.':getAll');
  $this->get('/resultat/[{id}]', \App\ApiControllers\ResultatController::class.':get');
  $this->get('/resultat/run/[{id}]', \App\ApiControllers\ResultatController::class.':getRun');
  $this->post('/resultat', \App\ApiControllers\ResultatController::class.':add');
})->add(new ApiAuthMiddleware($container));
