<?php

namespace App\Extensions;

use App\Entity\User;
use App\enums\Roles;
use App\Security\Middleware;
use App\Service\RepositoryService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtensions extends AbstractExtension {
    public function getFunctions(): array {
        return [
            new TwigFunction('is_granted', $this->isGranted(...)),
            new TwigFunction('path', $this->getPath(...)),
            new TwigFunction('assets', $this->getAssets(...)),
            new TwigFunction('flashes', $this->getFlashes(...)),
        ];
    }

    private function getFlashes(): array {
        $flashes = [];
        if (isset($_SESSION['flashes'])) {
            $flashes = $_SESSION['flashes'];
            unset($_SESSION['flashes']);
        }
        return $flashes;
    }

    private function isGranted(string $role): bool
    {
        $repositoryService = new RepositoryService();
        $middleware = new Middleware($repositoryService->getAuthTokenRepository());

        $currentUser = $middleware->getCurrentUser();
        if (!($currentUser instanceof User)) {
            return false;
        }

        return match ($role) {
            'ROLE_USER' => in_array(Roles::ROLE_USER, $currentUser->getRoles(), true),
            'ROLE_EDITOR' => in_array(Roles::ROLE_EDITOR, $currentUser->getRoles(), true),
            'ROLE_ADMIN' => in_array(Roles::ROLE_ADMIN, $currentUser->getRoles(), true),
            default => false,
        };
    }

    private function getPath(string $route, array $params = []): string
    {
        $url = $route;
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        return $url;
    }

    private function getAssets(string $path): string
    {
        return "/assets/$path";
    }
}
