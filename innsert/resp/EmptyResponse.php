<?php

namespace innsert\resp;

/**
 * Innsert PHP MVC Framework
 *
 * Response to send when none generated, echoes a string
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class EmptyResponse extends Response
{
	/**
	 * Response code set to 418 (Im a teapot)
	 *
	 * @access  public
	 * @var     int
	 */
	public $code = 418;

	/**
	 * Constructor
	 *
	 * Sets json and prepares headers
	 *
	 * @access	public
	 * @param	string	$message	Message to print
	 */
	public function __construct($message)
	{
		parent::__construct();
		$this->body = $message;
	}
}
