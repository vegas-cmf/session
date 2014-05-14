<?php
use Vegas\Session\Adapter\Files as SessionAdapter;

//Test Suite bootstrap
include __DIR__ . "/../vendor/autoload.php";

define('TESTS_ROOT_DIR', dirname(__FILE__));

$configArray = require_once dirname(__FILE__) . '/config.php';

$config = new \Phalcon\Config($configArray);
$di = new \Phalcon\DI\FactoryDefault();

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function () use ($config) {
    $sessionAdapter = new SessionAdapter($config->session->toArray());
    if (!$sessionAdapter->isStarted()) {
        $sessionAdapter->start();
    }
    return $sessionAdapter;
}, true);

$di->set('sessionManager', function() use ($di) {
    $session = new \Vegas\Session($di->get('session'));

    return $session;
}, true);

\Phalcon\DI::setDefault($di);