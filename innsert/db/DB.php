<?php

namespace innsert\db;

/**
 * Innsert PHP MVC Framework
 *
 * Helper class to get MysqlDatabase class statically
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class DB
{
	/**
	 * DBMysql instance
	 *
	 * @static
	 * @access	protected
	 * @var		DBMysql
	 */
	protected static $instance;

	/**
	 * Returns DBMysql instance
	 *
	 * @static
	 * @access	public
	 * @return	DBMysql
	 */
	public static function defaultInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new DBMysql();
		}
		return self::$instance;
	}
}
