<?php

namespace innsert\lib;

/**
 * Innsert PHP MVC Framework
 *
 * Interacts with DatePlus, represents an invalid date
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class NullDatePlus extends DatePlus
{
	/**
	 * Returns empty string
	 *
	 * @access	public
	 * @return	string
	 */
	public function toDB()
	{
		return '';
	}

	/**
	 * Returns empty string
	 *
	 * @access	public
	 * @param	string	$format		Requested format
	 * @return	string
	 */
	public function format($format)
	{
		return '';
	}
}
