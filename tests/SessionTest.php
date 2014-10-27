<?php
/**
 * This file is part of Vegas package
 *
 * @author Slawomir Zytko <slawek@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Vegas\Tests;

use Vegas\Tests\FakeSessionAdapter as SessionAdapter;
use \Phalcon\DI;
use \Vegas\Session;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        $config = DI::getDefault()->get('config');
        $di = DI::getDefault();

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
    }

    public function testSessionShouldNotStartAgain()
    {
        $session = DI::getDefault()->get('sessionManager');
        $this->assertInstanceOf('\Phalcon\Session\AdapterInterface', $session->getAdapter());
        $this->assertInstanceOf('\Vegas\Tests\FakeSessionAdapter', $session->getAdapter());
        $this->assertFalse($session->start());
    }

    public function testSessionShouldBeStarted()
    {
        $session = DI::getDefault()->get('sessionManager');
        $this->assertTrue($session->isStarted());
    }

    public function testSessionShouldStoreValue()
    {
        $session = DI::getDefault()->get('sessionManager');
        $session->set('test_val', 'foo');
        $this->assertTrue($session->has('test_val'));
        $this->assertEquals('foo', $session->get('test_val'));
        $this->assertFalse($session->has('test_val2'));
        $session->remove('test_val');
        $this->assertFalse($session->has('test_val2'));
    }

    public function testSessionIdShouldBeIdenticalWithSessionIdFunction()
    {
        $session = DI::getDefault()->get('sessionManager');
        $this->assertEquals(session_id(), $session->getId());
    }
} 