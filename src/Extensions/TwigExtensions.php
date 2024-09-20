<?php

namespace App\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtensions extends AbstractExtension {
    public function getFunctions(): array {
        return [
            new TwigFunction('is_logged', $this->isLogged(...)),
            new TwigFunction('is_granted', $this->isGranted(...)),
            new TwigFunction('path', $this->getPath(...)),
            new TwigFunction('assets', $this->getAssets(...)),
        ];
    }

    public function isLogged(): bool {
        return isset($_SESSION['user']);
    }

    public function isGranted(string $role): bool {
        return $this->isLogged() && $_SESSION['user']['role'] === $role;
    }

    public function getPath(string $route, array $params = []): string {
        $url = $route;
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        return $url;
    }

    public function getAssets(string $path): string {
        return "/assets/$path";
    }
}
