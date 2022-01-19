<?php

namespace innsert\db;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Property not found on model exception
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class PropertyNotFoundException extends Exception
{
	/**
	 * Constructor
	 *
	 * Personaliza el mensaje de error
	 *
	 * @access	public
	 * @param	string	$table			Model class or table
	 * @param	string	$property		Property or column name
	 */
	public function __construct($table, $property)
	{
		parent::__construct("Field not found ({$table}->{$property})");
	}
}