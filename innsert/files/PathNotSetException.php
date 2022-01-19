<?php

namespace innsert\files;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * File save path missing exception
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class PathNotSetException extends Exception
{
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	string	$name	Temp name of file
	 */
	public function __construct($name)
	{
		parent::__construct("Path not found ({$name})");
	}
}