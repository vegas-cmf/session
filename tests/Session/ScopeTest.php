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
use \Phalcon\Di;

class ScopeTestDiResolver
{
    public function resolve()
    {
        $config = Di::getDefault()->get('config');
        $di = Di::getDefault();

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

        Di::setDefault($di);
    }
}

class ScopeTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        (new ScopeTestDiResolver)->resolve();
    }

    public function testSessionScopeInterfaceInstance()
    {
        $session = Di::getDefault()->get('sessionManager');
        $session->addScope(new Session\Scope('test'));
        $this->assertInstanceOf('\Vegas\Session\ScopeInterface', $session->getScope('test'));
        $session->deleteScope('test');
    }

    public function testAddScopeInSession()
    {
        $session = Di::getDefault()->get('sessionManager');

        $session->addScope(new Session\Scope('test'));
        $this->assertTrue($session->scopeExists('test'));
        $this->assertInstanceOf('\Vegas\Session\Scope', $session->getScope('test'));
    }

    public function testCreateScopeInSession()
    {
        $session = Di::getDefault()->get('sessionManager');

        $this->assertInstanceOf('\Vegas\Session\Scope', $session->createScope('testCreateScope'));
        $this->assertTrue($session->scopeExists('testCreateScope'));
    }

    public function testDeleteScopeFromSession()
    {
        $session = Di::getDefault()->get('sessionManager');

        $session->deleteScope('test');
        $this->assertFalse($session->scopeExists('test'));
    }

    public function testSessionShouldNotCreateTwoScopesWithTheSameName()
    {
        $session = Di::getDefault()->get('sessionManager');

        $session->addScope(new Session\Scope('test'));

        $exception = null;
        try {
            $session->addScope(new Session\Scope('test'));
        } catch (\Exception $e) {
            $exception = $e;
        }
        $this->assertInstanceOf('\Vegas\Session\Exception\ScopeAlreadyExistsException', $exception);
        $this->assertNotEmpty($exception->getMessage());
    }

    public function testSessionScopeShouldStoreValue()
    {
        $session = Di::getDefault()->get('sessionManager');

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
        $session = Di::getDefault()->get('sessionManager');

        $exception = null;
        try {
            $session->getScope('test2');
        } catch (\Exception $e) {
            $exception = $e;
        }
        $this->assertInstanceOf('\Vegas\Session\Exception\ScopeNotExistsException', $exception);
        $this->assertNotEmpty($exception->getMessage());
    }

    public function testExceptionShouldBeThrownWhenScopeNameIsEmpty()
    {
        $session = Di::getDefault()->get('sessionManager');

        $exception = null;
        try {
            $session->addScope(new Session\Scope(''));
        } catch (\Exception $e) {
            $exception = $e;
        }
        $this->assertInstanceOf('\Vegas\Session\Exception\ScopeEmptyNameException', $exception);
        $this->assertNotEmpty($exception->getMessage());
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

        /**
         * check https://github.com/phalcon/cphalcon/issues/10116
         */

//        $scope2->remove('nextTest2');
//        $this->assertNull($scope2->nextTest2);
//
//        $scope2->nextTest3 = 'nextTest3';
//        unset($scope2->nextTest3);
//        $this->assertNull($scope2->nextTest3);
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
        $session = Di::getDefault()->get('sessionManager');
        $adapter = new SessionAdapter();

        $session->setAdapter($adapter);
        $this->assertInstanceOf('\Vegas\Tests\FakeSessionAdapter', $session->getAdapter());
    }

} 