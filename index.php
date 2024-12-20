<?php

require_once './vendor/autoload.php';
require_once './config.php';

use App\Controller\Admin\AdminPostCategoryController;
use App\Controller\Admin\AdminPostController;
use App\Controller\Admin\AdminUserController;
use App\Controller\Admin\DashboardController;
use App\Controller\Exceptions\NotFoundController;
use App\Controller\HomeController;
use App\Controller\PostController;
use App\Controller\SecurityController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routes = [
    '/' => [HomeController::class, 'index'],
    '/home' => [HomeController::class, 'index'],
	'/not-found' => [NotFoundController::class, 'index'],

    '/register' => [SecurityController::class, 'register'],
    '/login' => [SecurityController::class, 'login'],
    '/logout' => [SecurityController::class, 'logout'],

    '/posts' => [PostController::class, 'index'],
    '/posts/(\d+)' => [PostController::class, 'show'],
	'/comment/(\d+)/approve' => [PostController::class, 'approveComment'],
	'/comment/(\d+)/disapprove' => [PostController::class, 'disapproveComment'],
	'/comment/(\d+)/delete' => [PostController::class, 'deleteComment'],
	'/comment/(\d+)/edit' => [PostController::class, 'editComment'],

    '/admin' => [DashboardController::class, 'index'],

    '/admin/posts' => [AdminPostController::class, 'index'],
    '/admin/posts/create' => [AdminPostController::class, 'create'],
    '/admin/posts/(\d+)/edit' => [AdminPostController::class, 'edit'],
	'/admin/posts/(\d+)/edits' => [AdminPostController::class, 'listingEdits'],

	'/admin/categories' => [AdminPostCategoryController::class, 'index'],
	'/admin/categories/create' => [AdminPostCategoryController::class, 'create'],
	'/admin/categories/(\d+)/edit' => [AdminPostCategoryController::class, 'edit'],

	'/admin/users' => [AdminUserController::class, 'index'],
	'/admin/users/(\d+)/edit' => [AdminUserController::class, 'edit'],
];

session_start();

// Parcourir les routes pour trouver une correspondance
foreach ($routes as $pattern => $callback) {
    if (preg_match("#^$pattern$#", $uri, $matches)) {
        // Supprime le premier élément (correspondance complète)
        array_shift($matches);

        // Appeler le contrôleur et la méthode avec les paramètres capturés
        $controller = new $callback[0]();
        echo call_user_func_array([$controller, $callback[1]], $matches);
		exit();
    }
}

// Aucune route trouvée
header('Location: /not-found'.PHP_EOL);
exit();
