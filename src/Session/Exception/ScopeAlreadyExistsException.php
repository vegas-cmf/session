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
 * ScopeAlreadyExistsException is thrown when scope with provided name already exists.
 *
 * @package Vegas\Session\Exception
 */
class ScopeAlreadyExistsException extends SessionException
{
    protected $message = "Session namespace already exists";
} 