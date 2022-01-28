<?php

namespace innsert\core;

/**
 * Innsert PHP MVC Framework
 *
 * Requires PHP files validation for repeated files
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Loader
{
	/**
	 * Array of required files
	 *
	 * @static
	 * @access	public
	 * @var		array
	 */
	public static $items = [];

	/**
	 * If not on items array, requires the given file
	 *
	 * @static
	 * @access	public
	 * @param	string	$path	File path to require
	 * @throws	PhpFileNotFoundException
	 */
	public static function file(array $path)
	{
		$file = DIRECTORY . DS . join(DS, $path) . EXT;
		if (!in_array($file, self::$items)) {
			if (!file_exists($file)) {
				throw new PhpFileNotFoundException($file);
			}
			self::$items[] = $file;
			require $file;
		}
	}

	/**
	 * Reads json and returns array
	 *
	 * @static
	 * @access	public
	 * @param	string	$path		File path to require
	 * @param	bool	$asArray	Return json as an array
	 * @throws	PhpFileNotFoundException
	 * @return	array
	 */
	public static function json(array $path, $asArray = true)
	{
		$file = DIRECTORY . DS . join(DS, $path) . '.json';
		if (!file_exists($file)) {
			throw new JsonFileNotFoundException($file);
		}
		$json = json_decode(file_get_contents($file), $asArray);
		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new JsonParseFoundException($file);
		}
		return $json;
	}
}
