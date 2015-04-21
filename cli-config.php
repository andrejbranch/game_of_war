<?php

require_once "bootstrap.php";

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Yaml\Yaml;

$paths = array("src/GameOfWar/Entity");
$isDevMode = false;

// the connection configuration
$connectionParams = Yaml::parse(file_get_contents(__DIR__.'/config/database.yml'));

$driver = new AnnotationDriver(new AnnotationReader(), $paths);

$config = Setup::createConfiguration($isDevMode);
$config->setMetadataDriverImpl($driver);

$entityManager = EntityManager::create($connectionParams['database'], $config);

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
