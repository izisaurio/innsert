<?php

namespace innsert\mvc;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Controlled exception to be catched in controllers
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class ControllerControlledException extends Exception
{
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	string	$message	Error message
	 */
	public function __construct($message)
	{
		parent::__construct($message);
	}
}
