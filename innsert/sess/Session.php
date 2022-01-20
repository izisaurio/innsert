<?php

namespace innsert\sess;

use innsert\core\ArrayLike, innsert\lib\Request;

/**
 * Innsert PHP MVC Framework
 *
 * Session manager class
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Session extends ArrayLike
{
	/**
	 * Key under wich all values will be stored
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $namespace;

	/**
	 * Time in seconds for session to expire (0=limitless)
	 *
	 * @access	protected
	 * @var		int
	 */
	protected $lifetime = 0;

	/**
	 * Session cookie path
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $path = '/';

	/**
	 * Cookie domain
	 *
	 * @access	protected
	 * @var		int
	 */
	protected $domain = '';

	/**
	 * Https flag
	 *
	 * @access	protected
	 * @var		int
	 */
	protected $secure;

	/**
	 * Http only flag
	 *
	 * @access	protected
	 * @var		int
	 */
	protected $httponly = true;

	/**
	 * Constructor
	 *
	 * Sets options and starts session
	 *
	 * @access	public
	 */
	public function __construct()
	{
		if (session_status() == PHP_SESSION_NONE) {
			if (!isset($this->secure)) {
				$this->secure =
					Request::defaultInstance()->server('HTTPS', 'off') === 'on';
			}
			session_set_cookie_params(
				$this->lifetime,
				$this->path,
				$this->domain,
				$this->secure,
				$this->httponly
			);
			session_name('INNSERTFRAMEWORKSESS');
			session_start();
		}
		if (!isset($this->namespace)) {
			$this->namespace = str_replace(US, '_', PATH);
		}
		if (!isset($_SESSION[$this->namespace])) {
			$_SESSION[$this->namespace] = [];
		}
		$this->_items = $_SESSION[$this->namespace];
	}

	/**
	 * Destructor
	 *
	 * Ends session
	 *
	 * @access	public
	 */
	public function __destruct()
	{
		session_write_close();
	}

	/**
	 * Sets values for access on the instance as array keys
	 *
	 * @access	protected
	 */
	protected function updateSessionArray()
	{
		$_SESSION[$this->namespace] = $this->_items;
	}

	/**
	 * Add and item to session
	 *
	 * @access	public
	 * @param	mixed	$key	Session key
	 * @param	mixed	$value	Key value
	 */
	public function offsetSet($key, $value)
	{
		parent::offsetSet($key, $value);
		$this->updateSessionArray();
	}

	/**
	 * Unset session item by key
	 *
	 * @access	public
	 * @param	mixed	$key	Session key
	 */
	public function offsetUnset($key)
	{
		parent::offsetUnset($key);
		$this->updateSessionArray();
	}

	/**
	 * Gets item and deletes it
	 *
	 * @access	public
	 * @param	mixed	$key	Session key
	 */
	public function flash($key)
	{
		if (!isset($this[$key])) {
			return null;
		}
		$item = $this->_items[$key];
		unset($this[$key]);
		return $item;
	}

	/**
	 * Regenerates session id
	 *
	 * @access	public
	 */
	public function regenerate()
	{
		session_regenerate_id(true);
	}

	/**
	 * Clears content of session
	 *
	 * @access	public
	 */
	public function clear()
	{
		unset($_SESSION[$this->namespace]);
		$this->_items = [];
	}

	/**
	 * Destroys current session
	 *
	 * @access	public
	 */
	public function destroy()
	{
		session_unset();
		session_destroy();
		setcookie(session_name(), '', 1);
		session_regenerate_id(true);
	}
}
