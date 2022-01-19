<?php

namespace innsert\lib;

use \Datetime,
	\DateTimeZone,
	innsert\lang\Lang;

/**
 * Innsert PHP MVC Framework
 *
 * Extension to DateTime object
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class DatePlus extends Datetime
{
	/**
	 * Creates instance setting the format and value
	 *
	 * @static
	 * @access	public
	 * @param	string			$format		Format of date
	 * @param	string			$time		Value
	 * @param	DateTimeZone	$timezone	Timezone to use
	 * @return	DatePlus
	 */
	public static function createFromFormat($format, $time, $timezone = null)
	{
		$date = isset($timezone) ? date_create_from_format($format, $time, $timezone) : date_create_from_format($format, $time);
		return $date === false ? new NullDatePlus : (new self)->setTimestamp($date->getTimestamp());
	}

	/**
	 * Creates instance setting the format and value and default value when dates is not valid
	 *
	 * @static
	 * @access	public
	 * @param	string			$format		Format of date
	 * @param	string			$time		Value
	 * @param	mixed			$default	Default value used when not valid
	 * @param	DateTimeZone	$timezone	Timezone to use
	 * @return	DatePlus
	 */
	public static function createFromFormatWithDefault($format, $time, $default = null, $timezone = null)
	{
		$date = self::createFromFormat($format, $time, $timezone);
		return ($date instanceof NullDatePlus && isset($default)) ? $default : $date;
	}

	/**
	 * Creates instance setting the format and using a POST key for value, with default value
	 *
	 * @static
	 * @access	public
	 * @param	string			$name		Post key
	 * @param	string			$format		Format of date
	 * @param	mixed			$default	Default value used when not valid
	 * @param	DateTimeZone	$timezone	Timezone to use
	 * @return	DatePlus
	 */
	public static function createFromPost($name, $format, $default = null, $timezone = null)
	{
		return self::createFromFormatWithDefault($format, Request::defaultInstance()->post($name), $default, $timezone);
	}

	/**
	 * Creates instance setting the format and using a GET key for value, with default value
	 *
	 * @static
	 * @access	public
	 * @param	string			$name		Get key
	 * @param	string			$format		Format a date
	 * @param	mixed			$default	Default value used when not valid
	 * @param	DateTimeZone	$timezone	Timezone to use
	 * @return	DatePlus
	 */
	public static function createFromGet($name, $format, $default = null, $timezone = null)
	{
		return self::createFromFormatWithDefault($format, Request::defaultInstance()->get($name), $default, $timezone);
	}

	/**
	 * Creates a DatePlus instance with database formatted DATETIME (Y-m-d H:i:s)
	 *
	 * @static
	 * @access	public
	 * @param	string	$date	Date value
	 * @return	DatePlus
	 */
	public static function fromDB($date)
	{
		return new self($date);
	}

	/**
	 * Sets timezone to current instance
	 *
	 * @static
	 * @access	public
	 * @param	string	$timezone	Timezone value
	 * @return	DatePlus
	 */
	public function timezoneSet($timezone)
	{
		$this->setTimezone(new DateTimeZone($timezone));
		return $this;
	}

	/**
	 * Returns this instance date value in database DATETIME format (Y-m-d H:i:s)
	 *
	 * @access	public
	 * @return	string
	 */
	public function toDB()
	{
		return $this->format('Y-m-d H:i:s');
	}

	/**
	 * Returns this instance value in database DATE format (Y-m-d)
	 *
	 * @access	public
	 * @return	string
	 */
	public function toDBDate()
	{
		return $this->format('Y-m-d');
	}

	/**
	 * Returns this instance value in database TIME format (H:i:s)
	 *
	 * @access	public
	 * @return	string
	 */
	public function toDBTime()
	{
		return $this->format('H:i:s');
	}

	/**
	 * Sets current instance time to start of day (00:00:00)
	 *
	 * @access	public
	 * @return	DatePlus
	 */
	public function toDayStart()
	{
		$this->setTime(0, 0, 0);
		return $this;
	}

	/**
	 * Sets current instance time to end of day (23:59:59)
	 *
	 * @access	public
	 * @return	DatePlus
	 */
	public function toDayEnd()
	{
		$this->setTime(23, 59, 59);
		return $this;
	}
}