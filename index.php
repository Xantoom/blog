<?php

require_once './vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\controllers\HomeController;

$route = $_SERVER['REQUEST_URI'];

$loader = new FilesystemLoader('src/templates');
$twig = new Environment($loader);

echo (new HomeController($twig))->index();
