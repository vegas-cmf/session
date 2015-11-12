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

use Phalcon\Session\AdapterInterface;

class FakeSessionAdapter implements \Phalcon\Session\AdapterInterface
{
    private static $sessionStorage = array();

    private static $started = false;

    private static $options = array();

    private static $id = null;

    private static $name = null;

    /**
     * Starts session, optionally using an adapter
     *
     * @param array $options
     * @return bool
     */
    public function start($options = array())
    {
        if (self::$started) {
            return false;
        }

        self::$options = $options;
        self::$sessionStorage = array();
        self::$id = uniqid();
        self::$started = true;

        if (isset($options['name'])) {
            $this->setName($options['name']);
        }

        session_id(self::$id);

        return self::$started;
    }

    /**
     * Sets session options
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        self::$options = $options;
    }

    /**
     * Get internal options
     *
     * @return array
     */
    public function getOptions()
    {
        return self::$options;
    }

    /**
     * Gets a session variable from an application context
     *
     * @param string $index
     * @param mixed $defaultValue
     * @return mixed
     */
    public function get($index, $defaultValue = null)
    {
        if (!isset(self::$sessionStorage[$index])) {
            return $defaultValue;
        }
        return self::$sessionStorage[$index];
    }

    /**
     * Sets a session variable in an application context
     *
     * @param string $index
     * @param string $value
     */
    public function set($index, $value)
    {
        self::$sessionStorage[$index] = $value;
    }

    /**
     * Check whether a session variable is set in an application context
     *
     * @param string $index
     * @return boolean
     */
    public function has($index)
    {
        return isset(self::$sessionStorage[$index]);
    }

    /**
     * Removes a session variable from an application context
     *
     * @param string $index
     */
    public function remove($index)
    {
        unset(self::$sessionStorage[$index]);
    }

    /**
     * Returns active session id
     *
     * @return string
     */
    public function getId()
    {
        return self::$id;
    }

    /**
     * Check whether the session has been started
     *
     * @return boolean
     */
    public function isStarted()
    {
        return self::$started;
    }

    /**
     * Destroys the active session
     *
     * @return boolean
     */
    public function destroy($session_id = null)
    {
        self::$started = false;
        self::$options = array();
        self::$id = null;
        self::$sessionStorage = array();
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __isset($name)
    {
        $this->has($name);
    }

    public function __unset($name)
    {
        $this->remove($name);
    }

    /**
     * Set session name
     *
     * @param string $name
     */
    public function setName($name)
    {
        self::$name = $name;
    }

    /**
     * Get session name
     *
     * @return string
     */
    public function getName()
    {
        self::$name;
    }

    /**
     * \Phalcon\Session construtor
     *
     * @param array $options
     */
    public function __construct($options = null)
    {
        self::$options = $options;
        if (isset($options['name'])) {
            $this->setName($options['name']);
        }
    }

    /**
     * Regenerate session's id
     *
     * @param bool $deleteOldSession
     * @return AdapterInterface
     */
    public function regenerateId($deleteOldSession = true)
    {
        if ($deleteOldSession) {
            $this->destroy();
        }

        $this->start();

        return $this;
    }
}