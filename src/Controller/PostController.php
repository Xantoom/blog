<?php

namespace App\Controller;

class PostController extends AbstractController
{
    public function index(): string
    {
        return $this->render('pages/posts/index.html.twig', [
			'posts' => $this->getRepositoryService()->getPostRepository()->findBy([], ['createdAt' => 'DESC']),
		]);
    }
}
