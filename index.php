<?php

require_once './vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$route = $_SERVER['REQUEST_URI'];

$loader = new FilesystemLoader('src/templates');
$twig = new Environment($loader);

$className = match ($route) {
    '/' => 'Home',
    '/post/{id}' => 'Post',
    '/admin' => 'Admin',
    default => 'NotFound',
};

$controllerString = "App\controllers\\".$className.'Controller';
$controller = new $controllerString($twig);
echo $controller->index();
