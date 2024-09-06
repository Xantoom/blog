<?php

namespace App\Controller;

class HomeController extends AbstractController
{
    public function index(): string
    {
        return $this->render('index.html.twig', ['name' => 'Xavier']);
    }
}
