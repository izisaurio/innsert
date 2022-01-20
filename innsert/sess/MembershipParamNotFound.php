<?php

namespace innsert\sess;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Membership param missing exception
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class MembershipParamNotFound extends Exception
{
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	string	$param	The required param
	 */
	public function __construct($param)
	{
		parent::__construct(
			"The param ({$param}) is required by the membership"
		);
	}
}
