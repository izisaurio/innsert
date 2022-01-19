<?php

namespace innsert\sess;

/**
 * Innsert PHP MVC Framework
 *
 * Helper class to get Session class statically
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class Sess
{
	/**
	 * Session instance
	 *
	 * @static
	 * @access	protected
	 * @var		Session
	 */
	protected static $instance;

	/**
	 * Returns Session instance
	 *
	 * @static
	 * @access	public
	 * @return	Session
	 */
	public static function defaultInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new Session;
		}
		return self::$instance;
	}

	/**
	 * Closes a session with its id
	 *
	 * @static
	 * @access	public
	 * @param	string	$id		Session id
	 */
	public static function closeSessionId($id)
	{
		if (session_status() == PHP_SESSION_ACTIVE) {
			throw new SessionAlreadyStartedException();
		}
		session_id($id);
		session_start();
		session_destroy();
	}
}