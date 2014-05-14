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
 
namespace Vegas\Session\Exception;


use Vegas\Session\Exception as SessionException;

/**
 * ScopeEmptyNameException is thrown when string containing name of scope is empty
 *
 * @package Vegas\Session\Exception
 */
class ScopeNotExistsException extends SessionException
{
    protected $message = "Indicated session scope does not exist in session storage";
} 