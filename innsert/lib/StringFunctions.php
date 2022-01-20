<?php

namespace innsert\lib;

use innsert\core\Loader;

/**
 * Innsert PHP MVC Framework
 *
 * Helper functions for string values
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class StringFunctions
{
	/**
	 * Creates a password hash
	 *
	 * @static
	 * @access	public
	 * @param	string	$password	Password to hash
	 * @param	string	$salt		Salt
	 * @return	string
	 */
	public static function createPassword($password, $salt)
	{
		return password_hash("{$password}{$salt}", PASSWORD_DEFAULT);
	}

	/**
	 * Validates the password given
	 *
	 * @static
	 * @access	public
	 * @param	string	$password	The password to validate
	 * @param	string	$salt		Salt
	 * @param	string	$hash		The hash created by createPassword
	 * @return	string
	 */
	public static function validatePassword($password, $salt, $hash)
	{
		return password_verify("{$password}{$salt}", $hash);
	}

	/**
	 * Creates a random alphanumeric string
	 *
	 * The characters do not repeat (Max 60 characters)
	 *
	 * @static
	 * @access	public
	 * @param	int		$long	String size
	 * @param   string  $chars  String of characters to use
	 * @return	string
	 */
	public static function randomAlpha(
		$long = 10,
		$chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'
	) {
		return substr(str_shuffle($chars), 0, $long);
	}

	/**
	 * Creates a random alphanumeric string, skipping the ones given
	 *
	 * The characters do not repeat (Max 60 characters minus the ones to skip)
	 *
	 * @static
	 * @access	public
	 * @param	array	$skip	Characters to skip
	 * @param	int		$long	String size
	 * @return	string
	 */
	public static function randomAlphaWithSkips(array $skip, $long = 10)
	{
		$chars =
			'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$skipped = str_replace($skip, '', $chars);
		return substr(str_shuffle($skipped), 0, $long);
	}

	/**
	 * Returns a random alphanumeric string
	 *
	 * Can have repeated values
	 *
	 * @static
	 * @access	public
	 * @param	int		$long	String size
	 * @param   string  $chars  String of characters to use
	 * @return	string
	 */
	public static function randomAlphaLimitless(
		$long = 10,
		$chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'
	) {
		$result = '';
		$len = strlen($chars);
		for ($i = 0; $i < $long; $i++) {
			$result .= $chars[rand(0, $len - 1)];
		}
		return $result;
	}

	/**
	 * Returns a random number code
	 *
	 * Can have repeated values
	 *
	 * @static
	 * @access	public
	 * @param	int		$long	Code size
	 * @return	string
	 */
	public static function randomNumberCode($long = 5)
	{
		return str_pad(rand(0, pow(10, $long) - 1), $long, '0', STR_PAD_LEFT);
	}

	/**
	 * Retunrs unique string
	 *
	 * @static
	 * @access	public
	 * @return	string
	 */
	public static function uniqueString()
	{
		return uniqid(mt_rand(), true);
	}

	/**
	 * Returns if a string starts with given value
	 *
	 * @static
	 * @access	public
	 * @param	string	$compare	String to compare
	 * @param	string	$value		Starting value
	 * @return	bool
	 */
	public static function startsWith($compare, $value)
	{
		return substr($compare, 0, strlen($value)) === $value;
	}
}
