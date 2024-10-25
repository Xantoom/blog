<?php

namespace App\Controller;

class PostController extends AbstractController
{
    public function index(): string
    {
        $posts = $this->getRepositoryService()->getPostRepository()->findBy([], ['created_at' => 'DESC']);

        return $this->render('pages/posts/index.html.twig', [
            'posts' => $posts,
        ]);
    }

}
