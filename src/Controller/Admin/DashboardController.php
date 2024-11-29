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
			'last_posts' => $postRepository->findBy([], ['createdAt' => 'DESC'], 5),
			'last_comments_to_approve' => $commentRepository->findBy(['approval' => null], ['createdAt' => 'DESC'], 5),
		]);
	}
}
