<?php

use App\Fixtures\AppFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require 'vendor/autoload.php';

$config = new PhpFile('migrations.php');
$paths = [__DIR__ . '/src/Entity'];

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

// Charger les fixtures
$loader = new Loader();
$loader->addFixture(new AppFixtures());

// Exécuter les fixtures
$purger = new ORMPurger();
$executor = new ORMExecutor($entityManager, $purger);
$executor->execute($loader->getFixtures());
