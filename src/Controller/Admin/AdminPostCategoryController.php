<?php

namespace App\Controller\Admin;

use App\Entity\PostCategory;

class AdminPostCategoryController extends AdminController
{

	public function index(): string
	{
		$currentPage = $this->getRequestGet('page') ? (int) $this->getRequestGet('page') : 1;

		$postCategoryRepository = $this->getRepositoryService()->getPostCategoryRepository();
		$postCategories = $postCategoryRepository->findBy(
			[],
			['name' => 'ASC'],
			self::ITEMS_PER_PAGE,
			($currentPage - 1) * self::ITEMS_PER_PAGE
		);

		$nbCategories = $postCategoryRepository->count([]);

		return $this->render('pages/admin/postCategories/index.html.twig', [
			'postCategories' => $postCategories,
			'nbPages' => ceil($nbCategories / self::ITEMS_PER_PAGE),
			'currentPage' => $currentPage,
		]);
	}

	public function create(): string
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (null === $this->getRequestPost('name')) {
				$this->addFlash('danger', 'Name is required');
				$this->redirect('/admin/categories/create');
			}

			$name = $this->getRequestPost('name');

			$postCategory = $this->getRepositoryService()->getPostCategoryRepository()->findOneBy(['name' => $name]);
			if (null !== $postCategory) {
				$this->addFlash('danger', 'Category already exists');
				$this->redirect('/admin/categories/create');
			}

			$postCategory = new PostCategory();
			$postCategory->setName($name);

			$this->getEntityManager()->persist($postCategory);
			$this->getEntityManager()->flush();

			$this->addFlash('success', 'Category created');
			$this->redirect('/admin/categories');
		}

		return $this->render('pages/admin/postCategories/create.html.twig');
	}

	public function edit(string $id): string
	{
		$postCategory = $this->getRepositoryService()->getPostCategoryRepository()->find($id);

		if (!($postCategory instanceof PostCategory)) {
			$this->addFlash('danger', 'Category not found');
			$this->redirect('/admin/categories');
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (null === $this->getRequestPost('name')) {
				$this->addFlash('danger', 'Name is required');
				$this->redirect('/admin/categories/' . $id . '/edit');
			}

			$name = $this->getRequestPost('name');

			$postCategory = $this->getRepositoryService()->getPostCategoryRepository()->findOneBy(['name' => $name]);
			if (null !== $postCategory) {
				$this->addFlash('danger', 'Category with this name already exists');
				$this->redirect('/admin/categories/' . $id . '/edit');
			}

			$postCategory->setName($name);

			$this->getEntityManager()->persist($postCategory);
			$this->getEntityManager()->flush();

			$this->addFlash('success', 'Category updated');
			$this->redirect('/admin/categories');
		}

		return $this->render('pages/admin/postCategories/edit.html.twig', [
			'postCategory' => $postCategory,
		]);
	}
}
