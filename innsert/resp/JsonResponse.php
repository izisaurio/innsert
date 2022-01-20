<?php

namespace innsert\resp;

/**
 * Innsert PHP MVC Framework
 *
 * Json response
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class JsonResponse extends Response
{
	/**
	 * Json response default charset
	 *
	 * @access	public
	 * @var		array
	 */
	public $charset = ['charaset' => 'utf-8'];

	/**
	 * Constructor
	 *
	 * Sets json and prepares headers
	 *
	 * @access	public
	 * @param	array	$json	Json content
	 */
	public function __construct(array $json)
	{
		parent::__construct();
		$this->header('Content-Type', 'application/json', $this->charset);
		$this->body = json_encode($json, JSON_NUMERIC_CHECK);
	}
}
