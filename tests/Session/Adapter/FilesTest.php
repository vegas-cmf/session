<?php

namespace Vegas\Tests\Session\Adapter;

use \Phalcon\DI,
    \Vegas\Session\Adapter\Files as FilesAdapter;

class FilesTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var $di \Phalcon\DI
     */
    protected $di;

    /**
     * @var $sessionManager \Vegas\Session
     */
    protected $sessionManager;

    public static function setUpBeforeClass()
    {
        $di = DI::getDefault();
        $config = $di->get('config');

        /**
         * Start the session the first time some component request the session service
         */
        $di->set('session', function () use ($config) {
            $sessionAdapter = new FilesAdapter($config->session->toArray());
            if (!$sessionAdapter->isStarted()) {
                $sessionAdapter->start();
            }
            return $sessionAdapter;
        }, true);

        $di->set('sessionManager', function() use ($di) {
            $session = new \Vegas\Session($di->get('session'));

            return $session;
        }, true);

        DI::setDefault($di);
    }

    public function setUp()
    {
        $this->di = DI::getDefault();
        $this->sessionManager = $this->di->get('sessionManager');
    }

    public function testSessionShouldNotStartAgain()
    {
        $this->assertFalse($this->sessionManager->start());
    }

    public function testCookieParamsAfterAdapterStart()
    {
        $sessionConfig = $this->di->get('config')->session;
        $sessionParams = session_get_cookie_params();

        $this->assertEquals($sessionConfig->cookie_name, session_name());
        $this->assertEquals($sessionConfig->cookie_path, $sessionParams['path']);
        $this->assertEquals($sessionConfig->cookie_domain, $sessionParams['domain']);
        $this->assertEquals($sessionConfig->cookie_lifetime, $sessionParams['lifetime']);
        $this->assertEquals($sessionConfig->cookie_secure, $sessionParams['secure']);
        $this->assertEquals($sessionConfig->cookie_httponly, $sessionParams['httponly']);
    }

    public function testIfShortLifetimeWasChanged()
    {
        $this->assertTrue($this->sessionManager->setShortLifetime());
    }

    public function testIfCookieWasDestroyed()
    {
        $this->assertTrue($this->sessionManager->destroy());
    }

    public function testIfShortLifetimeWasNotChanged()
    {
        $this->assertFalse($this->sessionManager->setShortLifetime());
    }
}
