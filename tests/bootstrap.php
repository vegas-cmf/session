<?php
//Test Suite bootstrap
include __DIR__ . "/../vendor/autoload.php";

define('TESTS_ROOT_DIR', dirname(__FILE__));

$configArray = require_once dirname(__FILE__) . '/config.php';

$config = new \Phalcon\Config($configArray);
$di = new \Phalcon\Di\FactoryDefault();

$di->set('config', function() use ($config) {
    return $config;
});

\Phalcon\Di::setDefault($di);