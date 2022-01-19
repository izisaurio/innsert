<?php

namespace innsert\core;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * PHP file missing exception
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class PhpFileNotFoundException extends Exception
{
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	string	$file	PHP file missing
	 */
	public function __construct($file)
	{
		parent::__construct("Php file not found ({$file})");
	}
}