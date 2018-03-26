<?php

$container = $app->getContainer();

$container['auth'] = function ($container) {
	return new App\Auth\Auth;
};

$container['view'] = function ($container) {
	$dir = dirname(__DIR__);
    $view = new \Slim\Views\Twig($dir . '/app/views', [
        'cache' => false //$dir . '/tmp/cache'
    ]);

    // Initialise Slim et l'extension Twig
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

		$view->getEnvironment()->addGlobal('auth', [
			'check' => $container->auth->check(),
			'user'  => $container->auth->user()
		]);

		$view->getEnvironment()->addGlobal('flash', $container->flash);

    return $view;
};

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {
	return $capsule;
};

$container['validator'] = function ($container) {
	return new App\Validation\Validator;
};

$container['csrf'] = function ($container) {
	return new \Slim\Csrf\Guard;
};

$container['flash'] = function ($container) {
	return new \Slim\Flash\Messages;
};
