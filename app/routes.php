<?php

/*
 * Front-End
 */
$app->get('/', \App\PagesControllers\LieuController::class.':home')->setName('home');

$app->get('/lieu', \App\PagesControllers\LieuController::class.':getLieu')->setName('lieu');

// Authentification
$app->get('/auth/signup', \App\PagesControllers\AuthController::class.':getSignUp')->setName('signup');
$app->post('/auth/signup', \App\PagesControllers\AuthController::class.':postSignUp')->setName('signup');

$app->get('/auth/signin', \App\PagesControllers\AuthController::class.':getSignIn')->setName('signin');
$app->post('/auth/signin', \App\PagesControllers\AuthController::class.':postSignIn')->setName('signin');

$app->get('/auth/signout', \App\PagesControllers\AuthController::class.':getSignOut')->setName('signout');

/*
 * API lieu
 */
$app->get('/api/lieux', \App\ApiControllers\LieuController::class.':getAll');
$app->get('/api/lieu/[{id}]', \App\ApiControllers\LieuController::class.':get');
$app->get('/api/lieu/search/[{query}]', \App\ApiControllers\LieuController::class.':search');
$app->post('/api/lieu', \App\ApiControllers\LieuController::class.':add');
$app->delete('/api/lieu/[{id}]', \App\ApiControllers\LieuController::class.':delete');
$app->put('/api/lieu/[{id}]', \App\ApiControllers\LieuController::class.':update');
