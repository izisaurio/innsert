<?php

namespace innsert\val;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Rule not found on validations
 *
 * @author	isaac
 * @package	innsert
 * @version	1
 */
class RuleDoesNotExistException extends Exception
{
    /**
     * Constructor
     *
     * @access	public
     * @param	string	$rule		Rule not found
     * @param	string	$class		Validation class
     */
    public function __construct($class, $rule)
    {
        parent::__construct("Rule ({$rule}) not found in ({$class})");
    }
}