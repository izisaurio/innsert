<?php

namespace innsert\sess;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Exception thrown when trying to close by id an already started session
 *
 * @author	isaac
 * @package	innsert
 * @version	1
 */
class SessionAlreadyStartedException extends Exception
{
	/**
	 * Constructor
	 *
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct(
			'Is not posible to close an already started session'
		);
	}
}
