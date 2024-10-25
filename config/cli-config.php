<?php

require 'vendor/autoload.php';

use App\Fixtures\AppFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
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
    'driver'   => 'pgsql',
    'user'     => $_ENV['POSTGRES_USER'],
    'password' => $_ENV['POSTGRES_PASSWORD'],
    'dbname'   => $_ENV['POSTGRES_DB'],
    'host'     => $_ENV['POSTGRES_HOST'],
    'port'     => $_ENV['POSTGRES_PORT'],
]);

$entityManager = new EntityManager($connection, $ORMConfig);

return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));
