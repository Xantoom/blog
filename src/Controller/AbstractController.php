<?php

namespace App\Controller;

use App\Entity\User;
use App\enums\Roles;
use App\Extensions\TwigExtensions;
use App\Repository\AuthTokenRepository;
use App\Security\Middleware;
use App\Service\RepositoryService;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractController
{
    private Environment $twig;
    private ?User $currentUser;
    private readonly RepositoryService $repositoryService;
	protected Request $request;

    public function __construct()
    {
		$this->request = Request::createFromGlobals();

        $loader = new FilesystemLoader('src/templates');

        // New instance of Twig with custom extensions
        $twig = new Environment($loader);
        $twig->addExtension(new TwigExtensions());

        $this->twig = $twig;

        $this->repositoryService = new RepositoryService();

        $middleware = new Middleware($this->repositoryService->getAuthTokenRepository());
        $this->currentUser = $middleware->getCurrentUser();
    }

    protected function getRepositoryService(): RepositoryService
    {
        return $this->repositoryService;
    }

    protected function getEntityManager(): EntityManager
    {
        return $this->repositoryService->getEntityManager();
    }

    protected function getCurrentUser(): ?User
    {
        return $this->currentUser;
    }

    protected function isLogged(): bool
    {
        return $this->currentUser instanceof User;
    }

    protected function isGranted(string $role): bool
    {
        if (!$this->isLogged()) {
            return false;
        }

        return in_array($role, $this->currentUser->getRoles(), true);
    }

    protected function render(string $template, array $data = []): string
    {
        return $this->twig->render($template, $data);
    }

    protected function redirect(string $route): void
    {
        header("Location: $route".PHP_EOL);
        exit();
    }

    protected function addFlash(string $type, string $message): void
    {
        // if there is already a flash message of this type, create a new array
        if (isset($_SESSION['flashes'][$type])) {
            $_SESSION['flashes'][$type] = (array) $_SESSION['flashes'][$type];
            $_SESSION['flashes'][$type][] = $message;
            return;
        }

        // if there is no flash message of this type, create a new array
        $_SESSION['flashes'][$type] = [$message];
    }
}
