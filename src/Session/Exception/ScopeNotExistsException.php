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
