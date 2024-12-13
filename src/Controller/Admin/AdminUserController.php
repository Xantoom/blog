<?php

namespace App\Controller\Admin;

use App\Entity\User;

class AdminUserController extends AdminController
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
			$fields = ['firstname', 'lastname', 'email', 'roles', 'active'];
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

			$user
				->setFirstname($firstname)
				->setLastname($lastname)
				->setEmail($email)
				->setRoles($roles)
				->setActive($active)
			;

			$this->getEntityManager()->persist($user);
			$this->getEntityManager()->flush();

			$this->addFlash('success', 'User updated successfully');
			$this->redirect('/admin/users');
		}

		return $this->render('pages/admin/posts/edit.html.twig', [
			'user' => $user,
		]);
	}
}
