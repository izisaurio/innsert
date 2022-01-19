<?php

namespace innsert\lib;

/**
 * Innsert PHP MVC Framework
 *
 * Helper class to get HttpRequest class statically
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class Request
{
	/**
	 * HttpRequest instance
	 *
	 * @static
	 * @access	protected
	 * @var		HttpRequest
	 */
	protected static $instance;

	/**
	 * Returns HttpRequest instance
	 *
	 * @static
	 * @access	public
	 * @return	HttpRequest
	 */
	public static function defaultInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new HttpRequest;
		}
		return self::$instance;
	}
}
