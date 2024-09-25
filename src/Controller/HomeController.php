<?php

namespace App\Controller;

class HomeController extends AbstractController
{
    public function index(): string
    {
        return $this->render('pages/home/index.html.twig', ['name' => 'Xavier']);
    }
}
