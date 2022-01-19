<?php

namespace innsert\core;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Config file missing exception
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class ConfigFileNotFoundException extends Exception
{
	/**
	 * Constructor
	 *
	 * @param	string	$class	Class that requested the file
	 * @param	string	$file	Path of the config file
	 *
	 * @access	public
	 */
	public function __construct($class, $file)
	{
		parent::__construct("Configuration file not found ({$file}), for vlass ({$class})");
	}
}