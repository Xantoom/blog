<?php

namespace App\Controller\Admin;

class AdminCommentController extends AdminController
{

	public function index(): string
	{
		$currentPage = $_GET['page'] ?? 1;

		$commentRepository = $this->getRepositoryService()->getCommentRepository();
		$comments = $commentRepository->findBy(
			[],
			['createdAt' => 'DESC'],
			self::ITEMS_PER_PAGE,
			($currentPage - 1) * self::ITEMS_PER_PAGE
		);

		$nbComments = $commentRepository->count([]);

		return $this->render('pages/admin/comments/index.html.twig', [
			'comments' => $comments,
			'nbPages' => ceil($nbComments / self::ITEMS_PER_PAGE),
			'currentPage' => $currentPage,
		]);
	}
}
