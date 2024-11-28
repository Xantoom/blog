<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;

abstract class AdminController extends AbstractController
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->isLogged()) {
			$this->addFlash('error', 'You need to be logged in to access this page');
			$this->redirect('/login');
		}

		if (!$this->isGranted('ROLE_ADMIN')) {
			$this->addFlash('error', 'You need to be an admin to access this page');
			$this->redirect('/');
		}
	}
}
