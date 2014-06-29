<?php
/**
 * This file is part of Vegas package
 *
 * @author Slawomir Zytko <slawomir.zytko@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Vegas\Tests\Session;

use \Vegas\Session\Adapter\Mongo;
use Vegas\Session;
use \Phalcon\DI;

class ScopeTest extends \PHPUnit_Framework_TestCase
{
    public function testSessionScope()
    {
        @session_start();
        $session = DI::getDefault()->get('sessionManager');

        $session->addScope(new Session\Scope('test'));
        $this->assertTrue($session->scopeExists('test'));
        $this->assertInstanceOf('\Vegas\Session\ScopeInterface', $session->getScope('test'));

        $scope = $session->getScope('test');
        $this->assertEquals('test', $scope->getName());
        $scope->set('test', 'value2');
        $this->assertTrue($scope->has('test'));
        $this->assertEquals('value2', $scope->get('test'));

        $session->set('test', 'value1');
        $this->assertFalse($session->get('test') == $scope->get('test'));

        try {
            $scope2 = $session->getScope('test2');
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Vegas\Session\Exception\ScopeNotExistsException', $e);
            $session->addScope(new Session\Scope('test2'));
            $scope2 = $session->getScope('test2');
        }
        $this->assertInstanceOf('\Vegas\Session\ScopeInterface', $scope2);

        $scope2->set('test', 'value3');
        $this->assertFalse($scope2->get('test') == $scope->get('test'));

        try {
            $session->addScope(new Session\Scope('test'));
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Vegas\Session\Exception\ScopeAlreadyExistsException', $e);
        }

        try {
            $session->addScope(new Session\Scope(''));
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Vegas\Session\Exception\ScopeEmptyNameException', $e);
        }

        $scope2->nextTest = 'nextValue';

        $this->assertEquals('nextValue', $scope2->nextTest);
        $this->assertEquals('nextValue', $scope2->get('nextTest'));
        $this->assertTrue($scope2->has('nextTest'));
        $this->assertTrue(isset($scope2->nextTest));
        $scope2->set('nextTest2', 'nextValue2');
        $this->assertEquals('nextValue2', $scope2->nextTest2);

        $scope2->remove('nextTest2');
        $this->assertNull($scope2->nextTest2);

        $this->assertInstanceOf('\Phalcon\Session\BagInterface', $scope2->getSessionObject());

        $mongoAdapter = new Mongo(array('collection' => 'test'));
        $session->setAdapter($mongoAdapter);
        $this->assertInstanceOf('\Vegas\Session\Adapter\Mongo', $session->getAdapter());

        $scope2->destroy();

        $this->assertNull($scope->nextTest2);
    }
} 