<?php

namespace innsert\lang;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Label file missing exception
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class LabelsFileNotFoundException extends Exception
{
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	string	$file	Labels file
	 */
	public function __construct($file)
	{
		parent::__construct("Labels file not found ({$file})");
	}
}