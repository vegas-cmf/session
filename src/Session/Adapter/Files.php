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
 
namespace Vegas\Session\Adapter;

use \Phalcon\Session\AdapterInterface as AdapterInterface;

/**
 * @see http://docs.phalconphp.com/en/latest/api/Phalcon_Session_Adapter_Files.html
 * @package Vegas\Session\Adapter
 */
class Files extends \Phalcon\Session\Adapter\Files implements AdapterInterface
{
    /**
     * Starts the session
     * @return bool
     * @author Nikolaos Dimopoulos (https://github.com/phalcon/cphalcon/issues/277)
     */
    public function start()
    {
        // Check that session is not already started
        if ($this->isStarted()) {
            return false;
        }

        // Get current cookie options
        $options = $this->getCookieOptions();

        // Set cookie name
        session_name($options['name']);

        // Set cookie parameters
        session_set_cookie_params(
            $options['lifetime'], $options['path'], $options['domain'],
            $options['secure'], $options['httponly']
        );

        // Start session
        return parent::start();
    }

    /**
     * Destroys current session and removes session cookie
     * @return bool
     * @author Nikolaos Dimopoulos (https://github.com/phalcon/cphalcon/issues/277)
     */
    public function destroy($sessionId = null)
    {
        // Remove session cookie
        $options = $this->getCookieOptions();
        if (!setcookie($options['name'], '', -1)) {
            return false;
        }

        // Clean session data
        return parent::destroy($sessionId);
    }

    /**
     * Sets cookie lifetime to zero
     * @return bool
     */
    public function setShortLifetime()
    {
        if (!$this->isStarted()) {
            return false;
        }

        // Get cookie options
        $options = $this->getCookieOptions();

        // Short session, will be finished after browser will be closed
        $options['lifetime'] = 0;

        // Session id
        $id = session_id();

        // Set new cookie
        return setcookie(
            $options['name'], $id, $options['lifetime'], $options['path'],
            $options['domain'], $options['secure'], $options['httponly']
        );
    }


    /**
     * Returns current session cookie configuration
     * @return array
     * @author Nikolaos Dimopoulos (https://github.com/phalcon/cphalcon/issues/277)
     */
    public function getCookieOptions()
    {
        // Get default cookie options
        $options = session_get_cookie_params();

        // Cookie name
        $options['name'] = session_name();
        if (!empty($this->_options['cookie']['name'])) {
            $options['name'] = (string) $this->_options['cookie']['name'];
        }

        // Cookie lifetime
        if (!empty($this->_options['cookie']['lifetime'])) {
            $options['lifetime'] = (int) $this->_options['cookie']['lifetime'];
        }

        // Path
        if (!empty($this->_options['cookie']['path'])) {
            $options['path'] = (string) $this->_options['cookie']['path'];
        }

        // Domain
        if (!empty($this->_options['cookie']['domain'])) {
            $options['domain'] = (string) $this->_options['cookie']['domain'];
        }

        // Secure
        if (!empty($this->_options['cookie']['secure'])) {
            $options['secure'] = (bool) $this->_options['cookie']['secure'];
        }

        // Http only
        if (!empty($this->_options['cookie']['httponly'])) {
            $options['httponly'] = (bool) $this->_options['cookie']['httponly'];
        }

        return $options;
    }
} 