<?php

namespace innsert\sess;

use innsert\lib\Cookie,
	innsert\db\DBMapper,
	innsert\db\Params,
	innsert\lib\StringFunctions;

/**
 * Innsert PHP MVC Framework
 *
 * Cookie authentication for http session memberships
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class CookieDBAuthentication extends Authentication
{
	/**
	 * Database table mapper
	 *
	 * @access	public
	 * @var		DBMapper
	 */
	public $mapper;
	/**
	 * Cookie
	 *
	 * @access	public
	 * @var		Cookie
	 */
	public $cookie;

	/**
	 * Database column for cookie id
	 *
	 * @access	public
	 * @var		string
	 */
	public $column = 'cookie';

	/**
	 * Cookie id key
	 *
	 * @access	public
	 * @var		string
	 */
	public $cookieKey = 'temp';

	/**
	 * Constructor
	 *
	 * Sets mapper and cookie
	 *
	 * @access	public
	 * @param	DBMapper	$mapper		DBmapper
	 * @param	Cookie		$cookie		Cookie with id
	 */
	public function __construct(DBMapper $mapper, Cookie $cookie)
	{
		$this->mapper = $mapper;
		$this->cookie = $cookie;
	}

	/**
	 * Vlidate cookie id
	 *
	 * @access	public
	 * @return	bool
	 */
	public function validate()
	{
		if (!isset($this->cookie[$this->cookieKey])) {
			return false;
		}
		$params = new Params();
		$params->add('STRING', $this->cookie[$this->cookieKey]);
		$user = $this->mapper
			->where($this->modelSearch, '?')
			->limit(1)
			->find($params)
			->first();
		if (!$user) {
			return false;
		}
		$user->cookie = StringFunctions::randomAlphaLimitless(50);
		$user->save();
		$this->manualModel($user);
		return parent::validate();
	}
}
