<?php

namespace App\Controller\Admin;

class DashboardController extends AdminController
{
	public function index(): string
	{
		$postRepository = $this->getRepositoryService()->getPostRepository();
		$userRepository = $this->getRepositoryService()->getUserRepository();
		$commentRepository = $this->getRepositoryService()->getCommentRepository();
		$authTokenRepository = $this->getRepositoryService()->getAuthTokenRepository();

		return $this->render('pages/admin/dashboard.html.twig', [
			'posts' => count($postRepository->findAll()),
			'users' => count($userRepository->findAll()),
			'comments' => count($commentRepository->findAll()),
			'authTokens' => count($authTokenRepository->findAll()),
		]);
	}
}
