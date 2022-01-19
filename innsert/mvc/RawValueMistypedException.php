<?php

namespace innsert\mvc;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Raw value not allowed exception
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class RawValueMistypedException extends Exception
{
	/**
	 * Constructor
	 *
	 * @param	string	$type	Default value type
	 * @access	public
	 */
	public function __construct($type)
	{
		parent::__construct("RAW only allowes 'strings' and 'int' ({$type})");
	}
}