<?php

namespace App\Security;

use App\Entity\AuthToken;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class Middleware
{
    public function __construct(
        private readonly EntityRepository $authTokenRepository
    ) {
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

        $authToken = $this->authTokenRepository->findOneBy(['token' => $authTokenInSession], ['expiresAt' => 'DESC']);
        if (!($authToken instanceof AuthToken)) {
            unset($_SESSION['auth_token']);
            return null;
        }

        if ($authToken->getExpiresAt() < new \DateTimeImmutable()) {
            unset($_SESSION['auth_token']);
            return null;
        }

        return $authToken->getUser();
    }
}
