<?php

namespace App\Controller\Exceptions;

use App\Controller\AbstractController;

class NotFoundController extends AbstractController
{
    public function index(): string
    {
        return $this->render('errors/404.html.twig');
    }
}
