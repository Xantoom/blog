<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

$dbParams = [
    'driver'   => 'pgsql',
    'user'     => $_ENV['POSTGRES_USER'],
    'password' => $_ENV['POSTGRES_PASSWORD'],
    'dbname'   => $_ENV['POSTGRES_DB'],
    'host'     => $_ENV['POSTGRES_HOST'],
];

// Configuration de Doctrine
$config = ORMSetup::createAttributeMetadataConfiguration(
    [__DIR__ . '/src/Entity'],
    true
);

$connection = DriverManager::getConnection($dbParams, $config);

$entityManager = new EntityManager($connection, $config);
global $entityManager;
