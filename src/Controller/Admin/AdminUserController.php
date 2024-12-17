<?php

namespace App\Controller\Admin;

use AllowDynamicProperties;
use App\Entity\User;
use MailerSend\Helpers\Builder\EmailParams;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\MailerSend;

#[AllowDynamicProperties] class AdminUserController extends AdminController
{

	public function index(): string
	{
		if (!$this->isGranted('ROLE_ADMIN')) {
			$this->addFlash('danger', 'Access denied');
			$this->redirect('/admin');
		}

		$currentPage = $this->getRequestGet('page') ? (int) $this->getRequestGet('page') : 1;

		$userRepository = $this->getRepositoryService()->getUserRepository();
		$users = $userRepository->findBy(
			[],
			['createdAt' => 'DESC'],
			self::ITEMS_PER_PAGE,
			($currentPage - 1) * self::ITEMS_PER_PAGE
		);

		$nbUsers = $userRepository->count([]);

		return $this->render('pages/admin/users/index.html.twig', [
			'users' => $users,
			'nbPages' => ceil($nbUsers / self::ITEMS_PER_PAGE),
			'currentPage' => $currentPage,
		]);
	}

	public function edit(string $id): string
	{
		$user = $this->getRepositoryService()->getUserRepository()->find($id);

		if (!($user instanceof User)) {
			$this->addFlash('danger', 'User not found');
			$this->redirect('/admin/users');
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$fields = ['firstname', 'lastname', 'email', 'active'];
			foreach ($fields as $field) {
				if ($this->getRequestPost($field) === null) {
					$this->addFlash('danger', 'Your REQUEST is invalid. Please fill all the fields');
					$this->redirect('/admin/users/' . $user->getId() . '/edit');
				}
			}

			$firstname = $this->getRequestPost('firstname');
			$lastname = $this->getRequestPost('lastname');
			$email = $this->getRequestPost('email');
			$roles = (array) $this->getRequestPost('roles');
			$active = $this->getRequestPost('active');
			$password = $this->getRequestPost('password');
			
			if (!empty($password)) {
				// Send email
				$emailParams = (new EmailParams())
					->setFrom($_ENV['MAILERSEND_MAIL_ADDRESS'])
					->setFromName('Xavier Lauer')
					->setRecipients([new Recipient($email, $firstname . ' ' . $lastname)])
					->setSubject('Password updated')
					->setHtml('Your password has been updated by an admin. Your new password is: ' . $password)
				;

				(new MailerSend(['api_key' => $_ENV['MAILERSEND_API_KEY']]))->email->send($emailParams);
				
				$user->setPassword(password_hash($password, PASSWORD_DEFAULT));
				$this->addFlash('info', 'Password has been updated. User will receive an email');
			}

			$user
				->setFirstname($firstname)
				->setLastname($lastname)
				->setEmail($email)
				->setRoles($roles)
				->setActive($active)
			;

			$this->getEntityManager()->persist($user);
			$this->getEntityManager()->flush();
			
			if ($this->getCurrentUser()?->getId() === $user->getId()) {
				$this->addFlash('success', 'Your account has been updated. Please log in again');
				$this->redirect('/logout');
			}

			$this->addFlash('success', 'User updated successfully');
			$this->redirect('/admin/users');
		}

		return $this->render('pages/admin/users/edit.html.twig', [
			'user' => $user,
		]);
	}
}
