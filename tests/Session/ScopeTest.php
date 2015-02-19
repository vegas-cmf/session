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
 
namespace Vegas\Tests\Session;

use Vegas\Tests\FakeSessionAdapter as SessionAdapter;
use Vegas\Session;
use \Phalcon\DI;

class ScopeTest extends \PHPUnit_Framework_TestCase
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

    public function testSessionScopeInterfaceInstance()
    {
        $session = DI::getDefault()->get('sessionManager');
        $session->addScope(new Session\Scope('test'));
        $this->assertInstanceOf('\Vegas\Session\ScopeInterface', $session->getScope('test'));
        $session->deleteScope('test');
    }

    public function testAddScopeInSession()
    {
        $session = DI::getDefault()->get('sessionManager');

        $session->addScope(new Session\Scope('test'));
        $this->assertTrue($session->scopeExists('test'));
        $this->assertInstanceOf('\Vegas\Session\Scope', $session->getScope('test'));
    }

    public function testCreateScopeInSession()
    {
        $session = DI::getDefault()->get('sessionManager');

        $this->assertInstanceOf('\Vegas\Session\Scope', $session->createScope('testCreateScope'));
        $this->assertTrue($session->scopeExists('testCreateScope'));
    }

    public function testDeleteScopeFromSession()
    {
        $session = DI::getDefault()->get('sessionManager');

        $session->deleteScope('test');
        $this->assertFalse($session->scopeExists('test'));
    }

    public function testSessionShouldNotCreateTwoScopesWithTheSameName()
    {
        $session = DI::getDefault()->get('sessionManager');

        $session->addScope(new Session\Scope('test'));

        $exception = null;
        try {
            $session->addScope(new Session\Scope('test'));
        } catch (\Exception $e) {
            $exception = $e;
        }
        $this->assertInstanceOf('\Vegas\Session\Exception\ScopeAlreadyExistsException', $exception);
    }

    public function testSessionScopeShouldStoreValue()
    {
        $session = DI::getDefault()->get('sessionManager');

        $scope = $session->getScope('test');
        $this->assertEquals('test', $scope->getName());
        $scope->set('test', 'value2');
        $this->assertTrue($scope->has('test'));
        $this->assertEquals('value2', $scope->get('test'));

        $session->set('test', 'value1');
        $this->assertFalse($session->get('test') == $scope->get('test'));

    }

    public function testExceptionShouldBeThrownForNonExistingScope()
    {
        $session = DI::getDefault()->get('sessionManager');

        $exception = null;
        try {
            $session->getScope('test2');
        } catch (\Exception $e) {
            $exception = $e;
        }
        $this->assertInstanceOf('\Vegas\Session\Exception\ScopeNotExistsException', $exception);
    }

    public function testExceptionShouldBeThrownWhenScopeNameIsEmpty()
    {
        $session = DI::getDefault()->get('sessionManager');

        $exception = null;
        try {
            $session->addScope(new Session\Scope(''));
        } catch (\Exception $e) {
            $exception = $e;
        }
        $this->assertInstanceOf('\Vegas\Session\Exception\ScopeEmptyNameException', $exception);
    }

    public function testScopeGetterSetter()
    {
        $scope2 = new Session\Scope('test2');

        $scope2->nextTest = 'nextValue';

        $this->assertEquals('nextValue', $scope2->nextTest);
        $this->assertEquals('nextValue', $scope2->get('nextTest'));
        $this->assertTrue($scope2->has('nextTest'));
        $this->assertTrue(isset($scope2->nextTest));
        $scope2->set('nextTest2', 'nextValue2');
        $this->assertEquals('nextValue2', $scope2->nextTest2);

        $scope2->remove('nextTest2');
        $this->assertNull($scope2->nextTest2);

        $scope2->nextTest3 = 'nextTest3';
        unset($scope2->nextTest3);
        $this->assertNull($scope2->nextTest3);
    }

    public function testScopeStorageGetter() {
        $scope = new Session\Scope('testScope');
        $this->assertInstanceOf('\Phalcon\Session\Bag', $scope->getStorage());
    }

    public function testScopeDestroy()
    {
        $scope = new Session\Scope('testScope');
        $this->assertNull($scope->destroy());
    }

    public function testSessionAdapterSetter() {
        $session = DI::getDefault()->get('sessionManager');
        $adapter = new SessionAdapter();

        $session->setAdapter($adapter);
        $this->assertInstanceOf('\Vegas\Tests\FakeSessionAdapter', $session->getAdapter());
    }

} 