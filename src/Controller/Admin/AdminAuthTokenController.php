<?php

namespace App\Controller\Admin;

class AdminAuthTokenController extends AdminController
{

	public function index(): string
	{
		$currentPage = $_GET['page'] ?? 1;

		$authTokenRepository = $this->getRepositoryService()->getAuthTokenRepository();
		$authTokens = $authTokenRepository->findBy(
			[],
			['createdAt' => 'DESC'],
			self::ITEMS_PER_PAGE,
			($currentPage - 1) * self::ITEMS_PER_PAGE
		);

		$nbAuthTokens = $authTokenRepository->count([]);

		return $this->render('pages/admin/auth_tokens/index.html.twig', [
			'authTokens' => $authTokens,
			'nbPages' => ceil($nbAuthTokens / self::ITEMS_PER_PAGE),
			'currentPage' => $currentPage,
		]);
	}

}
