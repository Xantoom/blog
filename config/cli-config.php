<?php

require 'vendor/autoload.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\DBAL\DriverManager;

$config = new PhpFile('migrations.php');
$paths = [__DIR__ . '/../src/Entity'];

$ORMConfig = ORMSetup::createAttributeMetadataConfiguration($paths);
$connection = DriverManager::getConnection([
    'driver'   => 'pdo_pgsql',
    'user'     => 'user',
    'password' => 'password',
    'dbname'   => 'database',
    'host'     => 'database',
    'port'     => '5432',
    'memory' => true,
]);

$entityManager = new EntityManager($connection, $ORMConfig);

return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));
