<?php

namespace App\Controller;

use App\Entity\User;
use App\enums\Roles;
use App\Extensions\TwigExtensions;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractController
{
    protected Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader('src/templates');

        // New instance of Twig with custom extensions
        $twig = new Environment($loader);
        $twig->addExtension(new TwigExtensions());

        $this->twig = $twig;
    }

    protected function render(string $template, array $data = []): string
    {
        return $this->twig->render($template, $data);
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
    }

    protected function isLogged(): bool
    {
        return isset($_SESSION['user']);
    }

    protected function isGranted(Roles $role): bool
    {
        return $this->isLogged() && $_SESSION['user']['role'] === $role->name;
    }

    protected function addFlash(string $type, string $message): void
    {
        // if there is already a flash message of this type, create a new array
        if (isset($_SESSION['flashes'][$type])) {
            $_SESSION['flashes'][$type] = (array)$_SESSION['flashes'][$type];
            $_SESSION['flashes'][$type][] = $message;
            return;
        }

        // if there is no flash message of this type, create a new array
        $_SESSION['flashes'][$type] = [$message];
    }

    protected function getCurrentUser(): ?User
    {
        if (!$this->isLogged()) {
            return null;
        }

        $id = $_SESSION['user']['id'];

        // Get user from database


        // For now, return a new user
        return new User();
    }
}
