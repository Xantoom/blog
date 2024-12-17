<?php

namespace App\Controller\Admin;

class DashboardController extends AdminController
{
	public function index(): string
	{
		$postRepository = $this->getRepositoryService()->getPostRepository();
		$userRepository = $this->getRepositoryService()->getUserRepository();
		$commentRepository = $this->getRepositoryService()->getCommentRepository();
		$authTokens = $this->getCurrentlyAvailableAuthTokens();

		return $this->render('pages/admin/dashboard.html.twig', [
			'posts' => count($postRepository->findAll()),
			'users' => count($userRepository->findAll()),
			'comments' => count($commentRepository->findAll()),
			'authTokens' => count($authTokens),
			'last_posts' => $postRepository->findBy([], ['createdAt' => 'DESC'], 5),
			'last_comments_to_approve' => $commentRepository->findBy(['approval' => null], ['createdAt' => 'DESC'], 5),
		]);
	}

	private function getCurrentlyAvailableAuthTokens(): array
	{
		$authTokenRepository = $this->getRepositoryService()->getAuthTokenRepository();

		$qb = $authTokenRepository->createQueryBuilder('a');
		$qb
			->select('a')
			->where('a.createdAt < :currentDateTime')
			->andWhere('a.expiresAt > :currentDateTime')
			->setParameter('currentDateTime', new \DateTimeImmutable())
			->orderBy('a.createdAt', 'ASC')
		;

		return $qb->getQuery()->getResult();
	}
}
