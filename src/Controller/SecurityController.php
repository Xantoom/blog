<?php

namespace App\Controller;

use App\Entity\AuthToken;
use App\Entity\User;
use App\Repository\AuthTokenRepository;
use App\Repository\UserRepository;
use App\Service\RepositoryService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Random\RandomException;

class SecurityController extends AbstractController
{
    /**
     * @throws OptimisticLockException
     * @throws RandomException
     * @throws ORMException
     */
    public function register(): string
    {
        if ($this->isLogged()) {
            $this->addFlash('danger', 'You are already connected');
            $this->redirect('/');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password'], $_POST['password_confirmation'])) {
                $this->addFlash('danger', 'All fields are required');
                return $this->render('pages/security/register/register.html.twig');
            }

            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordConfirmation = $_POST['password_confirmation'];

            if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($passwordConfirmation)) {
                $this->addFlash('danger', 'All fields are required');
                return $this->render('pages/security/register/register.html.twig');
            }

            if ($password !== $passwordConfirmation) {
                $this->addFlash('danger', 'Passwords do not match');
                return $this->render('pages/security/register/register.html.twig');
            }

            if (strlen($password) <= 8) {
                $this->addFlash('danger', 'Password must be at least 8 characters long');
                return $this->render('pages/security/register/register.html.twig');
            }

            $userRepository = $this->getRepositoryService()->getUserRepository();
            $user = $userRepository->findOneBy(['email' => $email]);
            if ($user instanceof User) {
                $this->addFlash('danger', 'User already exists');
                return $this->render('pages/security/register/register.html.twig');
            }

            $user = new User();
            $user
                ->setFirstname($firstname)
                ->setLastname($lastname)
                ->setEmail($email)
                ->setPassword(password_hash($password, PASSWORD_DEFAULT))
            ;

            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();

            $authToken = new AuthToken();
            $authToken
                ->setExpiresAt((new \DateTimeImmutable())->modify('+7 days'))
                ->setToken(bin2hex(random_bytes(64)))
                ->setUser($user)
            ;

            $this->getEntityManager()->persist($authToken);
            $this->getEntityManager()->flush();

            // ENVOYER UN EMAIL

            $_SESSION['auth_token'] = $authToken->getToken();

            $this->addFlash('success', 'You are now registered');
            $this->redirect('/');
        }

        return $this->render('pages/security/register/register.html.twig');
    }

    /**
     * @throws OptimisticLockException
     * @throws RandomException
     * @throws ORMException
     */
    public function login(): string
    {
        if ($this->isLogged()) {
            $this->addFlash('danger', 'You are already connected');
            $this->redirect('/');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['email'], $_POST['password'])) {
                $this->addFlash('danger', 'All fields are required');
                return $this->render('pages/security/login/login.html.twig');
            }

            $email = $_POST['email'];
            $password = $_POST['password'];

            if (empty($email) || empty($password)) {
                $this->addFlash('danger', 'All fields are required');
                return $this->render('pages/security/login/login.html.twig');
            }

            $userRepository = $this->getRepositoryService()->getUserRepository();
            $user = $userRepository->findOneBy(['email' => $email]);
            if (!($user instanceof User)) {
                $this->addFlash('danger', 'User not found, please verify your email');
                return $this->render('pages/security/login/login.html.twig');
            }

            if (!password_verify($password, $user->getPassword())) {
                $this->addFlash('danger', 'Invalid password');
                return $this->render('pages/security/login/login.html.twig');
            }

            $authToken = new AuthToken();
            $authToken
                ->setCreatedAt(new \DateTimeImmutable())
                ->setExpiresAt((new \DateTimeImmutable())->modify('+1 day'))
                ->setToken(bin2hex(random_bytes(32)))
                ->setUser($user)
            ;

            $this->getEntityManager()->persist($authToken);
            $this->getEntityManager()->flush();

            $_SESSION['auth_token'] = $authToken->getToken();

            $this->addFlash('success', 'You are now connected');

            $this->redirect('/');
        }

        return $this->render('pages/security/login/login.html.twig');
    }

    public function logout(): void
    {
        if (!$this->isLogged()) {
            $this->addFlash('danger', 'You are not connected');
            $this->redirect('/');
        }

        if (isset($_SESSION['auth_token'])) {
            unset($_SESSION['auth_token']);
        }

        $this->addFlash('success', 'You are now disconnected');
        $this->redirect('/');
    }

}
