<?php

namespace innsert\sess;

use innsert\lib\Request, innsert\core\Defaults;

/**
 * Innsert PHP MVC Framework
 *
 * Membership with small validations for http sessions
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class UserMembership extends Membership
{
	/**
	 * Prefix for session values
	 *
	 * @access	public
	 * @var		string
	 */
	public $sessionKey = '__user__';

	/**
	 * Does two small validations for session "security"
	 *
	 * @access	protected
	 * @return	bool
	 */
	protected function sessionValidations()
	{
		$sess = $this->session[$this->sessionKey];
		if ($sess['time'] + 10800 < time()) {
			return false;
		}
		$this->session['time'] = time();
		$request = Request::defaultInstance();
		$checkIP = Defaults::defaultInstance()['sessionCheckIP'];
		if ($checkIP && $sess['address'] != $request->server('REMOTE_ADDR')) {
			return false;
		}
		return $sess['agent'] == $request->server('HTTP_USER_AGENT');
	}
}
