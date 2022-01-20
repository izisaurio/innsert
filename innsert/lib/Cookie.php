<?php

namespace innsert\lib;

use innsert\core\Forable;

/**
 * Innsert PHP MVC Framework
 *
 * Cookie management class
 *
 * @author	isaac
 * @package	innsert
 * @version	1
 */
class Cookie extends Forable
{
	/**
	 * Name of cookie
	 *
	 * @access	public
	 * @var		string
	 */
	public $name;

	/**
	 * Values to store in cookie, they will be writen as json, Can also be accessed with instance
	 *
	 * @access	public
	 * @var		array
	 */
	public $values = [];

	/**
	 * Cookie exists
	 *
	 * @access	public
	 * @var		bool
	 */
	public $exists = false;

	/**
	 * Time in seconds to expire
	 *
	 * @access	public
	 * @var		int
	 */
	public $expire = 0;

	/**
	 * Path of cookie
	 *
	 * @access	public
	 * @var		string
	 */
	public $path = '';

	/**
	 * Domain of cookie
	 *
	 * @access	public
	 * @var		string
	 */
	public $domain = '';

	/**
	 * Https only cookie
	 *
	 * @access	public
	 * @var		bool
	 */
	public $secure;

	/**
	 * Http access only cookie
	 *
	 * @access	public
	 * @var		bool
	 */
	public $httpOnly = true;

	/**
	 * Constructor
	 *
	 * Sets cookie name
	 *
	 * @access	public
	 * @param	string	$name	Cookie name
	 */
	public function __construct($name)
	{
		if (isset($_COOKIE[$name])) {
			$this->exists = true;
			$this->values = json_decode($_COOKIE[$name]);
			$this->_items = (array) $this->values;
		}
		$this->name = $name;
	}

	/**
	 * Sets cookie
	 *
	 * @access	public
	 * @param	mixed	$key		Key for json when adding one item|Array to be stored as json
	 * @param	mixed	$value		Value of above key|Null when array
	 * @param	int		$expire		Time in seconds of expiration
	 * @return 	bool
	 */
	public function set($key, $value = null, $expire = null)
	{
		if (!isset($this->secure)) {
			$this->secure =
				Request::defaultInstance()->server('HTTPS', 'off') === 'on';
		}
		if (!isset($expire)) {
			$expire = $this->expire;
		}
		$toSet = is_array($key) ? $key : [$key => $value];
		$this->_items = (array) $toSet;
		$this->values = $this->_items;
		$cookie = json_encode($toSet);
		return setcookie(
			$this->name,
			$cookie,
			$expire,
			$this->path,
			$this->domain,
			$this->secure,
			$this->httpOnly
		);
	}
}
