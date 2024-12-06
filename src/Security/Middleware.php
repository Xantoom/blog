<?php

namespace App\Security;

use App\Entity\AuthToken;
use App\Entity\User;
use App\Service\RepositoryService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class Middleware
{
	private RepositoryService $repositoryService;

    public function __construct() {
		$this->repositoryService = new RepositoryService();
    }

    public function getCurrentUser(): ?User
    {
        if (!isset($_SESSION['auth_token'])) {
            return null;
        }

        $authTokenInSession = $_SESSION['auth_token'];
        if (empty($authTokenInSession)) {
            return null;
        }

        $authToken = $this->repositoryService->getAuthTokenRepository()->findOneBy(['token' => $authTokenInSession], ['expiresAt' => 'DESC']);
        if (!($authToken instanceof AuthToken)) {
            unset($_SESSION['auth_token']);
            return null;
        }

        if ($authToken->getExpiresAt() < new \DateTimeImmutable()) {
			// We remove the token from the database if expired
	        $entityManager = $this->repositoryService->getEntityManager();
			$entityManager->remove($authToken);
			$entityManager->flush();
            unset($_SESSION['auth_token']);
            return null;
        }

        return $authToken->getUser();
    }
}
