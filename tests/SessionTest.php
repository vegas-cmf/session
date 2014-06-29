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
 
namespace Vegas\Tests;

use \Phalcon\DI;
use \Vegas\Session;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    public function testSessionStart()
    {
        $session = DI::getDefault()->get('sessionManager');
        $this->assertInstanceOf('\Vegas\Session\Adapter\AdapterInterface', $session->getAdapter());
        $this->assertFalse($session->start());
        $this->assertFalse($session->isStarted());
    }

    public function testSessionStorage()
    {
        $session = DI::getDefault()->get('sessionManager');
        @session_start();
        $session->set('test_val', 'value');
        $this->assertEquals('value', $session->get('test_val'));
        $this->assertTrue($session->has('test_val'));
        $this->assertFalse($session->has('test_val2'));
        $session->remove('test_val');
        $this->assertFalse($session->has('test_val'));
        $this->assertEquals(session_id(), $session->getId());

        $this->assertFalse($session->has('test_val2'));
    }
} 