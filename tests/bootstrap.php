<?php

$autoloader = require dirname(__DIR__) . '/vendor/autoload.php';

// Add the services file to the default service builder
$servicesFile = __DIR__ . '/services.json';
Guzzle\Tests\GuzzleTestCase::setServiceBuilder(Guzzle\Service\Builder\ServiceBuilder::factory($servicesFile));
