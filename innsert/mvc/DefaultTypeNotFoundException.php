<?php

namespace innsert\mvc;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Default value type missing exception
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class DefaultTypeNotFoundException extends Exception
{
	/**
	 * Constructor
	 *
	 * @param	string	$type	Type missing
	 * @access	public
	 */
	public function __construct($type)
	{
		parent::__construct("Missing type ({$type})");
	}
}
