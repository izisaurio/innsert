<?php

namespace innsert\lib;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Wrong param type exception
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class ParamTypeException extends Exception
{
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	string		$typeReceived	Type received
	 * @param	string		$typeExpected	Expected type
	 * @param	string		$param			Param name
	 */
	public function __construct($typeReceived, $typeExpected, $param)
	{
		parent::__construct(
			"Param error: expected ({$typeExpected}) - recieved ({$typeReceived}) - param ({$param})"
		);
	}
}
