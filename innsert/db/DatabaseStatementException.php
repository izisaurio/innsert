<?php

namespace innsert\db;

use innsert\core\Log, \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Exception when executing a database statement
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class DatabaseStatementException extends Exception
{
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	string	$message	Error message
	 * @param	string	$query		Sql sentence executing
	 */
	public function __construct($message, $query)
	{
		parent::__construct(
			"Error in sentence (Err: {$message}) - (Query: {$query})"
		);
		Log::add('database', $this->getMessage());
	}
}
