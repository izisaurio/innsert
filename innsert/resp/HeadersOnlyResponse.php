<?php

namespace innsert\resp;

/**
 * Innsert PHP MVC Framework
 *
 * Response to sends the headers only
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class HeadersOnlyResponse extends Response
{
	/**
	 * Response code set to 200 (Ok)
	 *
	 * @access  public
	 * @var     int
	 */
	public $code = 200;

	/**
	 * Constructor
	 *
	 * Sets additional headers
	 *
	 * @access	public
	 * @param	array	$headers	headers to add to response
	 */
	public function __construct(array $headers = [])
	{
		parent::__construct();
		if (!empty($headers)) {
			$this->headers($headers);
		}
	}

	/**
	 * Sends response
	 *
	 * @access	public
	 */
	public function send()
	{
		$this->writeHeaders();
		exit();
	}
}
