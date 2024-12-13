<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;

abstract class AdminController extends AbstractController
{
	protected const ITEMS_PER_PAGE = 10;

	public function __construct()
	{
		parent::__construct();

		if (!$this->isLogged()) {
			$this->addFlash('danger', 'You need to be logged in to access this page');
			$this->redirect('/login');
		}

		if (!$this->isGranted('ROLE_ADMIN') || !$this->isGranted('ROLE_EDITOR')) {
			$this->addFlash('danger', 'You need to be an admin to access this page');
			$this->redirect('/');
		}
	}
}
