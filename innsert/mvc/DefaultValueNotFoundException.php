<?php

namespace innsert\mvc;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Default value missing exception
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class DefaultValueNotFoundException extends Exception
{
	/**
	 * Constructor
	 *
	 * @param	string	$type		Default value type
	 * @param	string	$value		Model key
	 * @access	public
	 */
	public function __construct($type, $value)
	{
		parent::__construct("Default key not found ({$type}->{$value})");
	}
}