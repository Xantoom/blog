<?php

namespace App\Extensions;

use App\Entity\User;
use App\enums\Roles;
use App\Security\Middleware;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtensions extends AbstractExtension {
    public function getFunctions(): array {
        return [
            new TwigFunction('is_granted', $this->isGranted(...)),
            new TwigFunction('path', $this->getPath(...)),
            new TwigFunction('assets', $this->getAssets(...)),
            new TwigFunction('flashes', $this->getFlashes(...)),
            new TwigFunction('currentUser', $this->getCurrentUser(...)),
        ];
    }

	private function getCurrentUser(): ?User
	{
		return (new Middleware())->getCurrentUser();
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
        $middleware = new Middleware();

        $currentUser = $middleware->getCurrentUser();
        if (null === $currentUser) {
            return false;
        }
		
		$roles = $currentUser->getRoles();
		if (in_array(Roles::ROLE_ADMIN->value, $roles, true)) {
			return true;
		}
		
        return in_array($role, $roles, true);
    }

	private function getPath(string $route, ?array $params = []): string
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
