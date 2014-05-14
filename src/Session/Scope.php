<?php
/**
 * This file is part of Vegas package
 *
 * @author Slawomir Zytko <slawomir.zytko@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-cmf-session
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vegas\Session;

use Phalcon\Session\Bag;
use Vegas\Session\Exception\ScopeEmptyNameException;

/**
 *
 * @see http://docs.phalconphp.com/en/latest/api/Phalcon_Session_Bag.html
 * @package Vegas\Session
 */
class Scope implements ScopeInterface
{
    /**
     * Name of scope
     *
     * @var
     */
    protected $name;

    /**
     * @var \Phalcon\Session\Bag
     */
    protected $sessionObject;

    /**
     * {@ineritdoc}
     *
     * @throws ScopeEmptyNameException
     */
    public function __construct($name)
    {
        if (strlen(trim($name)) == 0) {
            throw new ScopeEmptyNameException();
        }
        
        $this->name = $name;
        $this->sessionObject = new Bag($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getSessionObject()
    {
        return $this->sessionObject;
    }

    /**
     * {@inheritdoc}
     */
    public function set($property, $value)
    {
        $this->sessionObject->set($property, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function get($property)
    {
        return $this->sessionObject->get($property);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($property)
    {
        $this->sessionObject->remove($property);
    }

    /**
     * {@inheritdoc}
     */
    public function destroy()
    {
        $this->sessionObject->destroy();
    }

    /**
     * {@inheritdoc}
     */
    public function has($property)
    {
        return $this->sessionObject->has($property);
    }

    public function __set($property, $value)
    {
        $this->set($property, $value);
    }

    public function __get($property)
    {
        return $this->get($property);
    }

    public function __isset($property)
    {
        return $this->has($property);
    }
} 