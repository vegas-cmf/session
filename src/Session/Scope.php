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

namespace Vegas\Session;

use Phalcon\Session\Bag;
use Vegas\Session\Exception\ScopeEmptyNameException;

/**
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
    protected $storage;

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
        $this->storage = new Bag($name);
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
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * {@inheritdoc}
     */
    public function set($property, $value)
    {
        $this->storage->set($property, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function get($property)
    {
        return $this->storage->get($property);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($property)
    {
        $this->storage->remove($property);
    }

    /**
     * {@inheritdoc}
     */
    public function destroy()
    {
        $this->storage->destroy();
    }

    /**
     * {@inheritdoc}
     */
    public function has($property)
    {
        return $this->storage->has($property);
    }

    /**
     * Magic setter
     * @param $property
     * @param $value
     */
    public function __set($property, $value)
    {
        $this->set($property, $value);
    }

    /**
     * Magic getter
     *
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->get($property);
    }

    /**
     * Allows to use isset directly on object
     *
     * @param $property
     * @return bool|mixed
     */
    public function __isset($property)
    {
        return $this->has($property);
    }

    /**
     * Allows to use unset directly on object
     *
     * @param $property
     */
    public function __unset($property)
    {
        $this->remove($property);
    }
}
