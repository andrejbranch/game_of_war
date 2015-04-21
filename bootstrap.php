<?php

/**
 * Bootstraps the game of war application.
 * Initializing Doctrine ORM, the EntityManager, and the database,
 * as well as setting up the dependency injection container,
 * loading services into the container, and loading configurations
 * from the config files.
 *
 * @version 1.01
 * @author Andre Jon Branchizio <andrejbranchizio@gmail.com>
 */

require_once 'vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

// build the container
$container = new ContainerBuilder();

$container->setParameter('appDir', $appDir = __DIR__);

$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/config'));
$loader->load('services.yml');
$loader->load('config.yml');

// override values
if (file_exists(__DIR__.'/config/local.yml')) {
    $loader->load('local.yml');
}

// initialize entity manager

AnnotationRegistry::registerFile(__DIR__.'/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

$paths = array("src/GameOfWar/Entity");
$isDevMode = false;

// the connection configuration
$connectionParams = Yaml::parse(file_get_contents(__DIR__.'/config/database.yml'));
$driver = new AnnotationDriver(new AnnotationReader(), $paths);
$config = Setup::createConfiguration($isDevMode);
$config->setMetadataDriverImpl($driver);

$em = EntityManager::create($connectionParams['database'], $config);

$container->set('entity_manager', $em);
