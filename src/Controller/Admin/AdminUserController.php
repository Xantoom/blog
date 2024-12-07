<?php

namespace App\Controller\Admin;

class AdminUserController extends AdminController
{

	public function index(): string
	{
		$currentPage = $_GET['page'] ?? 1;

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

}
