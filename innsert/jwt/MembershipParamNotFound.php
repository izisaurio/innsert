<?php

namespace innsert\jwt;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Membership param missing exception
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class MembershipParamNotFound extends Exception
{
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	string	$param	Membership param required
	 */
	public function __construct($param)
	{
		parent::__construct("The param ({$param}) is required by membership");
	}
}
