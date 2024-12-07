<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\PostPublish;

class AdminPostController extends AdminController
{

	public function index(): string
	{
		$currentPage = $this->getRequestGet('page') ? (int) $this->getRequestGet('page') : 1;

		$postRepository = $this->getRepositoryService()->getPostRepository();
		$posts = $postRepository->findBy(
			[],
			['createdAt' => 'DESC'],
			self::ITEMS_PER_PAGE,
			($currentPage - 1) * self::ITEMS_PER_PAGE
		);

		$nbPosts = $postRepository->count([]);

		return $this->render('pages/admin/posts/index.html.twig', [
			'posts' => $posts,
			'nbPages' => ceil($nbPosts / self::ITEMS_PER_PAGE),
			'currentPage' => $currentPage,
		]);
	}

	public function create(): string
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$fields = ['title', 'content', 'preview', 'banner', 'category', 'published'];
			foreach ($fields as $field) {
				if ($this->getRequestPost($field) === null) {
					$this->addFlash('error', 'Your REQUEST is invalid. Please fill all the fields');
					$this->redirect('/admin/posts/create');
				}
			}

			$title = $this->getRequestPost('title');
			$content = $this->getRequestPost('content');
			$preview = $this->getRequestPost('preview');
			$banner = $this->getRequestPost('banner');
			$category = $this->getRepositoryService()->getPostCategoryRepository()->find($this->getRequestPost('category'));
			$published = $this->getRequestPost('published');

			if (null === $category) {
				$this->addFlash('error', 'Category not found');
				$this->redirect('/admin/posts/create');
			}

			$postPublish = null;
			if ('true' === $published) {
				$postPublish = new PostPublish();
				$postPublish
					->setPublished(true)
					->setPublishedAt(new \DateTimeImmutable())
					->setPublishedBy($this->getCurrentUser())
				;
			}

			$post = new Post();
			$post
				->setBanner($banner)
				->setCategory($category)
				->setContent($content)
				->setCreatedAt(new \DateTimeImmutable())
				->setCreatedBy($this->getCurrentUser())
				->setPreview($preview)
				->setTitle($title)
				->setViews(0)
			;

			if (null !== $postPublish) {
				$post->addPublish($postPublish);
			}

			$this->getEntityManager()->persist($post);
			$this->getEntityManager()->flush();

			$this->addFlash('success', 'Post created successfully');
			$this->redirect('/admin/posts');
		}

		return $this->render('pages/admin/posts/create.html.twig');
	}

	public function edit(string $id): string
	{
		$post = $this->getRepositoryService()->getPostRepository()->find($id);

		if (!($post instanceof Post)) {
			$this->addFlash('error', 'Post not found');
			$this->redirect('/admin/posts');
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$fields = ['title', 'content', 'preview', 'banner', 'category', 'published'];
			foreach ($fields as $field) {
				if ($this->getRequestPost($field) === null) {
					$this->addFlash('error', 'Your REQUEST is invalid. Please fill all the fields');
					$this->redirect('/admin/posts/' . $post->getId() . '/edit');
				}
			}

			$title = $this->getRequestPost('title');
			$content = $this->getRequestPost('content');
			$preview = $this->getRequestPost('preview');
			$banner = $this->getRequestPost('banner');
			$category = $this->getRepositoryService()->getPostCategoryRepository()->find($this->getRequestPost('category'));
			$published = $this->getRequestPost('published');

			if (null === $category) {
				$this->addFlash('error', 'Category not found');
				$this->redirect('/admin/posts/' . $post->getId() . '/edit');
			}

			$post
				->setBanner($banner)
				->setCategory($category)
				->setContent($content)
				->setPreview($preview)
				->setTitle($title)
			;

			$postPublish = $post->getPublish();
			if ('true' === $published) {
				if (null === $postPublish) {
					$postPublish = new PostPublish();
					$postPublish
						->setPublished(true)
						->setPublishedAt(new \DateTimeImmutable())
						->setPublishedBy($this->getCurrentUser());
					$post->addPublish($postPublish);
				}
			} else {
				if (null !== $postPublish) {
					$post->removePublish($postPublish);
				}
			}

			$this->getEntityManager()->persist($post);
			$this->getEntityManager()->flush();

			$this->addFlash('success', 'Post updated successfully');
			$this->redirect('/admin/posts');
		}

		return $this->render('pages/admin/posts/edit.html.twig', [
			'post' => $post,
		]);
	}
}
