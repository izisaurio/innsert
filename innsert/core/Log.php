<?php

namespace innsert\core;

/**
 * Innsert PHP MVC Framework
 *
 * Saves text to a log file
 * Uses app/logs in root folder
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Log
{
	/**
	 * Adds a message to log file
	 *
	 * @static
	 * @access	public
	 * @param	string	$fileName	Log file name
	 * @param	string	$message	Message
	 * @throws	LogFileNotFoundException
	 */
	public static function add($fileName, $message)
	{
		$file = 'app' . DS . 'logs' . DS . $fileName . '.log';
		if (!file_exists($file)) {
			throw new LogFileNotFoundException($file);
		}
		file_put_contents(
			$file,
			$message . ' :: ' . date('r') . PHP_EOL,
			FILE_APPEND
		);
	}

	/**
	 * Adds a var_export to log file
	 *
	 * @static
	 * @access	public
	 * @param	string	$fileName	Log file name
	 * @param	mixed	$var		Var to export
	 */
	public static function exp($fileName, $var)
	{
		self::add($fileName, var_export($var, true));
	}
}
