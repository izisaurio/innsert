<?php

namespace innsert\core;

/**
 * Innsert PHP MVC Framework
 *
 * Helper class to get DefaultConfigs class statically
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Defaults
{
	/**
	 * DefaultConfigs instance
	 *
	 * @static
	 * @access	protected
	 * @var		DefaultConfigs
	 */
	protected static $instance;

	/**
	 * Returns DefaultConfigs instance
	 *
	 * @static
	 * @access	public
	 * @return	DefaultConfigs
	 */
	public static function defaultInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new DefaultConfigs;
		}
		return self::$instance;
	}
}