<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\PostEdit;
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

	public function listingEdits(string $id): string
	{
		$currentPage = $this->getRequestGet('page') ? (int) $this->getRequestGet('page') : 1;

		$post = $this->getRepositoryService()->getPostRepository()->find($id);

		if (!($post instanceof Post)) {
			$this->addFlash('danger', 'Post not found');
			$this->redirect('/admin/posts');
		}

		$edits = $this->getRepositoryService()->getPostEditRepository()->findBy(
			['post' => $post],
			['editedAt' => 'DESC'],
			self::ITEMS_PER_PAGE,
			($currentPage - 1) * self::ITEMS_PER_PAGE
		);

		$nbEdits = $this->getRepositoryService()->getPostEditRepository()->count(['post' => $post]);

		return $this->render('pages/admin/posts/listing_edits.html.twig', [
			'post' => $post,
			'edits' => $edits,
			'nbPages' => ceil($nbEdits / self::ITEMS_PER_PAGE),
			'currentPage' => $currentPage,
		]);
	}

	public function create(): string
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$fields = ['title', 'content', 'preview', 'banner', 'category', 'published'];
			foreach ($fields as $field) {
				if ($this->getRequestPost($field) === null) {
					$this->addFlash('danger', 'Your REQUEST is invalid. Please fill all the fields');
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
				$this->addFlash('danger', 'Category not found');
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

		return $this->render('pages/admin/posts/create.html.twig', [
			'categories' => $this->getRepositoryService()->getPostCategoryRepository()->findBy([], ['name' => 'ASC']),
		]);
	}

	public function edit(string $id): string
	{
		$post = $this->getRepositoryService()->getPostRepository()->find($id);

		if (!($post instanceof Post)) {
			$this->addFlash('danger', 'Post not found');
			$this->redirect('/admin/posts');
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$fields = ['title', 'content', 'preview', 'banner', 'category', 'published'];
			foreach ($fields as $field) {
				if ($this->getRequestPost($field) === null) {
					$this->addFlash('danger', 'Your REQUEST is invalid. Please fill all the fields');
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
				$this->addFlash('danger', 'Category not found');
				$this->redirect('/admin/posts/' . $post->getId() . '/edit');
			}

			$postEdit = new PostEdit();
			$postEdit
				->setBanner($banner)
				->setCategory($category)
				->setContent($content)
				->setEditedBy($this->getCurrentUser())
				->setEditedAt(new \DateTimeImmutable())
				->setPreview($preview)
				->setPost($post)
				->setTitle($title)
			;

			$this->getEntityManager()->persist($postEdit);
			$this->getEntityManager()->flush();

			$postPublish = $post->getPublishes()->first();
			$publish = (bool) $published;

			if ((null === $postPublish) || (null !== $postPublish && $publish !== $postPublish->getPublished())) {
				$newPostPublish = new PostPublish();
				$newPostPublish
					->setPost($post)
					->setPublished($publish)
					->setPublishedAt(new \DateTimeImmutable())
					->setPublishedBy($this->getCurrentUser())
				;
				$post->addPublish($newPostPublish);
			}

			$this->getEntityManager()->persist($post);
			$this->getEntityManager()->flush();

			$this->addFlash('success', 'Post updated successfully');
			$this->redirect('/admin/posts');
		}

		return $this->render('pages/admin/posts/edit.html.twig', [
			'post' => $post,
			'categories' => $this->getRepositoryService()->getPostCategoryRepository()->findBy([], ['name' => 'ASC']),
		]);
	}
}
