<?php

namespace innsert\lang;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Label missing exception
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class LabelNotFoundException extends Exception
{
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	string	$file		Label file
	 * @param	string	$key		Key
	 * @param	string	$language	Language
	 */
	public function __construct($file, $key, $language)
	{
		parent::__construct("Label not found ({$file}->{$key}:{$language})");
	}
}