<?php

$container = $app->getContainer();

$container['view'] = function ($container) {
	$dir = dirname(__DIR__);
    $view = new \Slim\Views\Twig($dir . '/app/views', [
        'cache' => false //$dir . '/tmp/cache'
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['pdo'] = function () {
    $server     = 'localhost';
    $username   = 'root';
    $password   = '';
    $database   = 'database';

	$pdo = new PDO("mysql:host=$server;dbname=$database", $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	return $pdo;
};

$container['db'] = function ($container) {
	return new Database($container->pdo);
};
