<?php

namespace innsert\views;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Master view layout template not found
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class MasterViewNotFoundException extends Exception
{
	/**
	 * Constructor
	 *
	 * @param	string	$file	Template path
	 * @access	public
	 */
	public function __construct($file)
	{
		parent::__construct("'Master view not found' ({$file})");
	}
}
