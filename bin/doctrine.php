<?php
// bin/doctrine

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

// Adjust this path to your actual bootstrap.php
require __DIR__ . '/../config/cli-config.php';

ConsoleRunner::run(
    new SingleManagerProvider($entityManager)
);
