<?php

namespace innsert\core;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Log file missing exception
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class LogFileNotFoundException extends Exception
{
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	string	$file	File missing
	 */
	public function __construct($file)
	{
		parent::__construct("Log file not found ({$file})");
	}
}
