<?php

namespace innsert\core;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Json file parsing exception
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class JsonParseException extends Exception
{
	/**
	 * Constructor
	 *
	 * @param	string	$file	Path of the json file
	 *
	 * @access	public
	 */
	public function __construct($file)
	{
		parent::__construct("Json parsing failed ({$file})");
	}
}
