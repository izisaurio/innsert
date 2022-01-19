<?php

namespace innsert\resp;

use innsert\lib\Request;

/**
 * Innsert PHP MVC Framework
 *
 * Response base class
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
abstract class Response
{
	/**
	 * Response code
	 *
	 * @access	public
	 * @var		int
	 */
	public $code = 200;

	/**
	 * Server protocl
	 *
	 * @access	public
	 * @var		string
	 */
	public $protocol;

	/**
	 * Error messages with respective code
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $messages = [
		200 => 'OK',
		204 => 'No Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		304 => 'Not Modified',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		403 => 'Forbidden',
		404 => 'Not Found',
		410 => 'Gone',
		418 => 'I\'m a teapot',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		503 => 'Service Unavailable',
		550 => 'Permission denied'
	];

	/**
	 * Response body
	 *
	 * @access	public
	 * @var		string
	 */
	public $body;

	/**
	 * Response headers
	 *
	 * @access	public
	 * @var		array
	 */
	public $headers = [];

	/**
	 * Constructor
	 *
	 * Sets protocl and framework header
	 *
	 * @access	public
	 */
	public function __construct()
	{
		if (!isset($this->protocol)) {
			$this->protocol = Request::defaultInstance()->server('SERVER_PROTOCOL');
		}
		$this->header('X-Powered-By', 'Innsert Framework <izi.isaac@gmail.com>');
	}

	/**
	 * Adds a header to response
	 *
	 * @access	public
	 * @param	string	$type		Header type
	 * @param	string	$value		Header value
	 * @param	array	$adds		Aditional header values
	 * @return	Response
	 */
	public function header($type, $value, array $adds = array())
	{
		if (empty($adds)) {
			$this->headers[] = "{$type}:{$value}";
		} else {
			$this->headers[] = $type . ':' . $value . '; ' . urldecode(http_build_query($adds, '', ';'));
		}
		return $this;
	}

	/**
	 * Adds a headers to response
	 *
	 * @access	public
	 * @param	array	$headers	Adds headers as an array
	 * @return	Response
	 */
	public function headers(array $headers)
	{
		foreach ($headers as $key => $value) {
			if (is_int($key)) {
				$this->header($value[0], $value[1], $value[2]);
			}
			else {
				$this->header($key, $value);
			}
		}
		return $this;
	}

	/**
	 * Sends response
	 *
	 * @access	public
	 */
	public function send()
	{
		$this->writeHeaders();
		echo $this->body;
		exit;
	}

	/**
	 * Writes headers
	 *
	 * @access	protected
	 */
	protected function writeHeaders()
	{
		$message = array_key_exists($this->code, $this->messages) ? $this->messages[$this->code] : 'n/a';
		header("{$this->protocol} {$this->code} {$message}", true, $this->code);
		foreach ($this->headers as $header) {
			header($header);
		}
	}
}