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
 
namespace Vegas\Session;

/**
 *
 * @package Vegas\Session
 */
interface ScopeInterface
{
    /**
     * Returns the name of session namespace
     *
     * @return string
     */
    public function getName();

    /**
     * Returns an instance of session object from current namespace
     *
     * @return mixed
     */
    public function getSessionObject();

    /**
     * Sets value by name
     *
     * @param $property
     * @param $value
     * @return mixed
     */
    public function set($property, $value);

    /**
     * Returns stored value by name
     *
     * @param $property
     * @return mixed
     */
    public function get($property);

    /**
     * Determines if indicated value is stored in session
     *
     * @param $property
     * @return mixed
     */
    public function has($property);

    /**
     * Removes stored value by name
     *
     * @param $property
     */
    public function remove($property);

    /**
     * Destroys session scope
     *
     * @return mixed
     */
    public function destroy();
} 