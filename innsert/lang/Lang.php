<?php

namespace innsert\lang;

/**
 * Innsert PHP MVC Framework
 *
 * Helper class to get Language class statically
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class Lang
{
	/**
	 * Labguage instance
	 *
	 * @static
	 * @access	protected
	 * @var		Language
	 */
	protected static $instance;

	/**
	 * Returns Language instance
	 *
	 * @static
	 * @access	public
	 * @return	Language
	 */
	public static function defaultInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new Language();
		}
		return self::$instance;
	}
}
