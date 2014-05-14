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
 
namespace Vegas\Session\Adapter;

/**
 * @see http://docs.phalconphp.com/en/latest/api/Phalcon_Session_Adapter_Files.html
 * @package Vegas\Session\Adapter
 */
class Files extends \Phalcon\Session\Adapter\Files implements AdapterInterface
{
    /**
     * Starts the session
     * @return bool|void
     */
    public function start()
    {
        if ($this->_started) {
            return false;
        }

        // Configure cookie lifetime
        $lifetime = 0;
        if (!empty($this->_options['cookie_lifetime'])) {
            $lifetime = (int) $this->_options['cookie_lifetime'];
        }

        // Configure cookie path
        $path = "/";
        if (!empty($this->_options['cookie_path'])) {
            $lifetime = $this->_options['cookie_path'];
        }

        // Configure cookie domain
        $domain = null;
        if (!empty($this->_options['cookie_domain'])) {
            $domain = $this->_options['cookie_domain'];
        }

        // Configure if cookies should be transfered only via protected connection
        $secure = false;
        if (!empty($this->_options['cookie_secure'])) {
            $secure = (bool) $this->_options['cookie_secure'];
        }

        // Configure cookie access level
        $httponly = false;
        if (!empty($this->_options['cookie_httponly'])) {
            $httponly = (bool) $this->_options['cookie_httponly'];
        }

        // Set cookie parameters
        session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);

        // Start session
        return parent::start();
    }
} 