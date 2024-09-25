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
            new TwigFunction('flashes', $this->getFlashes(...)),
            new TwigFunction('dump', $this->getDump(...)),
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

    public function getFlashes(): array
    {
        $flashes = $_SESSION['flashes'] ?? [];
        unset($_SESSION['flashes']);
        return $flashes;
    }

    public function getDump(): void
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            echo '<div class="bg-dark text-white p-3 my-2 rounded">';
            echo '<pre class="mb-0">';

            var_dump($arg);

            echo '</pre>';
            echo '</div>';
        }
    }
}
