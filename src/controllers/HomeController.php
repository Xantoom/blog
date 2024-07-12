<?php

namespace App\controllers;

use Twig\Environment;

class HomeController
{
    public function __construct(
        public Environment $twig
    ) {
    }

    public function index(): string
    {
        return $this->twig->render('../templates/index.html.twig', [
            'name' => 'Test',
        ]);
    }
}
