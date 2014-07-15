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

namespace Vegas;

use Vegas\Session\Adapter\AdapterInterface;
use Vegas\Session\Exception\ScopeAlreadyExistsException;
use Vegas\Session\Exception\ScopeNotExistsException;
use Vegas\Session\Scope;
use Vegas\Session\ScopeInterface;

/**
 * Class Session
 *
 * @method start()
 * @method setOptions($options)
 * @method getOptions()
 * @method get($index, $defaultValue = null)
 * @method set($index, $value)
 * @method has($index)
 * @method remove($index)
 * @method getId()
 * @method isStarted()
 * @method destroy()
 *
 * @package Vegas
 */
final class Session
{

    /**
     * Session scopes storage
     *
     * @var array
     */
    private $storage = array();

    /**
     * @var \Phalcon\Session\AdapterInterface
     */
    private $adapter;

    /**
     * Sets session adapter
     *
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Calls function on session adapter
     *
     * @param $name
     * @param $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        return call_user_func_array(array($this->adapter, $name), $args);
    }

    /**
     * @return \Phalcon\Session\AdapterInterface|AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Sets session adapter
     *
     * @param AdapterInterface $adapter
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Stores new session scope
     *
     * @param ScopeInterface $scope
     * @throws Session\Exception\ScopeAlreadyExistsException
     */
    public function addScope(ScopeInterface $scope)
    {
        if ($this->scopeExists($scope->getName())) {
            throw new ScopeAlreadyExistsException();
        }
        $this->appendSessionScope($scope);
    }

    /**
     * Creates new session scope
     *
     * @param $name
     * @return Scope
     */
    public function createScope($name)
    {
        $scope = new Scope($name);
        $this->addScope($scope);

        return $scope;
    }

    /**
     * Determines session scope existing
     *
     * @param $name
     * @return bool
     */
    public function scopeExists($name)
    {
        return array_key_exists($name, $this->storage) && $this->storage[$name] instanceof ScopeInterface;
    }

    /**
     * Appends session scope to storage
     *
     * @param ScopeInterface $scope
     */
    private function appendSessionScope(ScopeInterface $scope)
    {
        $this->storage[$scope->getName()] = $scope;
    }

    /**
     * Returns session scope object
     *
     * @param $name
     * @throws ScopeNotExistsException
     * @return mixed
     */
    public function getScope($name)
    {
        if (!$this->scopeExists($name)) {
            throw new ScopeNotExistsException();
        }
        return $this->storage[$name];
    }
}