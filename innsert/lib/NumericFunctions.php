<?php

namespace innsert\lib;

/**
 * Innsert PHP MVC Framework
 *
 * Helper function for numeric data
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class NumericFunctions
{
	/**
	 * Compares two numbers to be equal rounding to given decimals
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$first		First number
	 * @param	mixed	$second		Second number
	 * @param	int		$decimals	Decimals to round to
	 * @throws	ParamTypeException
	 */
	public static function equal($first, $second, $decimals = 2)
	{
		if (!is_numeric($first)) {
			throw new ParamTypeException(gettype($first), 'Numeric', 'first');
		}
		if (!is_numeric($second)) {
			throw new ParamTypeException(gettype($second), 'Numeric', 'second');
		}
		return (round($first, $decimals) == round($second, $decimals));
	}

	/**
	 * Compares two numbers to first be greater than second rounding to given decimals
	 *
	 * @static
	 * @access	public
	 * @param	mixed	$first		First number
	 * @param	mixed	$second		Second number
	 * @param	int		$decimals	Decimals to round to
	 * @throws	ParamTypeException
	 */
	public static function greater($first, $second, $decimals = 2)
	{
		if (!is_numeric($first)) {
			throw new ParamTypeException(gettype($first), 'Numeric', 'first');
		}
		if (!is_numeric($second)) {
			throw new ParamTypeException(gettype($second), 'Numeric', 'second');
		}
		return (round($first, $decimals) > round($second, $decimals));
	}
}