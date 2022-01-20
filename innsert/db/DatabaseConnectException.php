<?php

namespace innsert\db;

use innsert\core\Log, \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Database connection expetion
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class DatabaseConnectException extends Exception
{
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	int		$code	Error code
	 */
	public function __construct($code)
	{
		parent::__construct(
			"A connection could not be established to the database (Err: {$code})"
		);
		Log::add('database', $this->getMessage());
	}
}
