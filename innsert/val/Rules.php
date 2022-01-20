<?php

namespace innsert\val;

use \DateTime;

/**
 * Innsert PHP MVC Framework
 *
 * Class with validation functions, all static
 *
 * @author	isaac
 * @package	innsert
 * @version	1
 */
class Rules
{
	/**
	 * Validates int number
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @return	bool
	 */
	public static function INT($value)
	{
		return ctype_digit($value) || is_int($value);
	}

	/**
	 * Validates numeric value
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @return	bool
	 */
	public static function NUMERIC($value)
	{
		return is_numeric($value);
	}

	/**
	 * Validates decimal number
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @return	bool
	 */
	public static function DECIMAL($value)
	{
		return is_numeric($value);
	}

	/**
	 * Validates text with little special chars allowed
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @return	bool
	 */
	public static function TEXT($value)
	{
		return preg_match(
			"#^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑäëïöüÄËÏÖÜ’ ?!%\+\-,\.;\$¿¡=\:´_\/\\\@\(\)\#'\*|\r\n]+$#",
			$value
		);
	}

	/**
	 * Validates alphanumeric text, with accents
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @return	bool
	 */
	public static function ALPHA($value)
	{
		return preg_match('#^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑäëïöüÄËÏÖÜ’ ]+$#', $value);
	}

	/**
	 * Validates datetime
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @return	bool
	 */
	public static function DATETIME($value)
	{
		return preg_match(
			'#^([123]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))(\s)(?:([01]?\d|2[0-3]):([0-5]?\d):)?([0-5]?\d)$#',
			$value
		);
	}

	/**
	 * Validates a date
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @return	bool
	 */
	public static function DATE($value)
	{
		return preg_match(
			'#^([123]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))$#',
			$value
		);
	}

	/**
	 * Validates a time
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @return	bool
	 */
	public static function TIME($value)
	{
		return preg_match(
			'#^(?:([01]?\d|2[0-3]):([0-5]?\d):)?([0-5]?\d)$#',
			$value
		);
	}

	/**
	 * Validates a timestamp
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @return	bool
	 */
	public static function TIMESTAMP($value)
	{
		return self::INT($value) && (int) $value >= 0;
	}

	/**
	 * Validates a boolean
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @return	bool
	 */
	public static function BOOL($value)
	{
		return in_array(
			$value,
			[true, false, 1, 0, 'yes', 'no', '1', '0'],
			true
		);
	}

	/**
	 * Validates email
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @return	bool
	 */
	public static function EMAIL($value)
	{
		return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
	}

	/**
	 * Validates text min characters
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @param	int		$length		Min size
	 * @return	bool
	 */
	public static function MIN_LENGTH($value, $length)
	{
		return strlen($value) >= $length;
	}

	/**
	 * Validates text max characters
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @param	int		$length		Max size
	 * @return	bool
	 */
	public static function MAX_LENGTH($value, $length)
	{
		return strlen($value) <= $length;
	}

	/**
	 * Validates min number value
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @param	int		$size		Min value
	 * @return	bool
	 */
	public static function MIN($value, $size)
	{
		return $value >= $size;
	}

	/**
	 * Validates number max value
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @param	int		$size		Max value
	 * @return	bool
	 */
	public static function MAX($value, $size)
	{
		return $value <= $size;
	}

	/**
	 * Validates a regular expression
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @param	string	$regex		Regular expression
	 * @return	bool
	 */
	public static function REGEX($value, $regex)
	{
		return preg_match($regex, $value);
	}

	/**
	 * Validates greater than another
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @param	mixed	$compare	Value grater than this
	 * @return	bool
	 */
	public static function GREATER($value, $compare)
	{
		return $value > $compare;
	}

	/**
	 * Validates date greater than another
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @param	mixed	$compare	Value grater than this
	 * @return	bool
	 */
	public static function GREATERDATE($value, $compare)
	{
		return DateTime::createFromFormat('Y-m-d', $value) >
			DateTime::createFromFormat('Y-m-d', $compare);
	}

	/**
	 * Validates datetime greater than another
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @param	mixed	$compare	Value grater than this
	 * @return	bool
	 */
	public static function GREATERDATETIME($value, $compare)
	{
		return DateTime::createFromFormat('Y-m-d H:i:s', $value) >
			DateTime::createFromFormat('Y-m-d H:i:s', $compare);
	}

	/**
	 * Validates value less than another
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @param	mixed	$compare	Value less than this
	 * @return	bool
	 */
	public static function LESS($value, $compare)
	{
		return $value < $compare;
	}

	/**
	 * Validates date less than another
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @param	mixed	$compare	Value less than this
	 * @return	bool
	 */
	public static function LESSDATE($value, $compare)
	{
		return DateTime::createFromFormat('Y-m-d', $value) <
			DateTime::createFromFormat('Y-m-d', $compare);
	}

	/**
	 * Validates datetime less than another
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @param	mixed	$compare	Value less than this
	 * @return	bool
	 */
	public static function LESSDATETIME($value, $compare)
	{
		return DateTime::createFromFormat('Y-m-d H:i:s', $value) <
			DateTime::createFromFormat('Y-m-d H:i:s', $compare);
	}

	/**
	 * Validates value equal to another
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$value		Value to validate
	 * @param	mixed	$compare	Value equal to this
	 * @return	bool
	 */
	public static function EQUAL($value, $compare)
	{
		return $value == $compare;
	}
}
