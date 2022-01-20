<?php

namespace innsert\lib;

use innsert\files\Uploader, \stdClass;

/**
 * Innsert PHP MVC Framework
 *
 * Current request values manager
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class HttpRequest
{
	/**
	 * Current request method (GET, POST, PUT, ETC.)
	 *
	 * @access	public
	 * @var		string
	 */
	public $method;

	/**
	 * Apache headers (apache_request_headers)
	 *
	 * @access	public
	 * @param	array
	 */
	public $apacheHeaders = [];

	/**
	 * Values received by $_POST
	 *
	 * @access	public
	 * @var		RequestParamValues
	 */
	public $post;

	/**
	 * Values received by $_GET
	 *
	 * @access	public
	 * @var		RequestParamValues
	 */
	public $get;

	/**
	 * Values received in the body as a json
	 *
	 * @access	public
	 * @var		RequestParamValues
	 */
	public $body;

	/**
	 * Constructor
	 *
	 * Sets request mehtos, post and get values
	 *
	 * @access	public
	 */
	public function __construct()
	{
		$this->method = $this->server('REQUEST_METHOD');
		$this->post = new RequestParamValues($_POST);
		$this->get = new RequestParamValues($_GET);
	}

	/**
	 * Shortcut to POST RequestParamValues
	 *
	 * Returns a POST value or the give default
	 *
	 * @access	public
	 * @param	string	$name		POST key
	 * @param	mixed	$default	Default value if key not found
	 * @return	mixed
	 */
	public function post($name, $default = null)
	{
		return $this->post->value($name, $default);
	}

	/**
	 * Shortcut to GET RequestParamValues
	 *
	 * Returns a GET value or the give default
	 *
	 * @access	public
	 * @param	string	$name		GET key
	 * @param	mixed	$default	Default value if key not found
	 * @return	mixed
	 */
	public function get($name, $default = null)
	{
		return $this->get->value($name, $default);
	}

	/**
	 * Extract the RequestParamValues of a POST or GET array value
	 *
	 * @access	public
	 * @param	array	$collection		The POST or GET array value
	 * @return	RequestParamValues
	 */
	public function extract(array $values)
	{
		return new RequestParamValues($values);
	}

	/**
	 * Returns the body of the request as a string
	 *
	 * @access	public
	 * @param	string	$name		GET key
	 * @param	mixed	$default	Default value if key not found
	 * @return	mixed
	 */
	public function body($name, $default = null)
	{
		if (!isset($this->body)) {
			$contents = file_get_contents('php://input');
			$body = empty($contents) ? [] : json_decode($contents, true);
			$this->body = is_array($body)
				? new RequestParamValues($body)
				: new RequestParamValues([]);
		}
		return $this->body->value($name, $default);
	}

	/**
	 * Returns a server value ($_SERVER)
	 *
	 * @access	public
	 * @param	string	$name		SERVER Key
	 * @param	mixed	$default	Default value if key not found
	 * @return	mixed
	 */
	public function server($name, $default = null)
	{
		return isset($_SERVER[$name]) ? $_SERVER[$name] : $default;
	}

	/**
	 * Returns an apache header value
	 *
	 * @access	public
	 * @param	string	$name		Apache header key
	 * @param	mixed	$default	Default value if key not found
	 * @return	mixed
	 */
	public function apacheHeader($name, $default = null)
	{
		if (empty($this->apacheHeaders)) {
			$this->apacheHeaders = array_change_key_case(
				apache_request_headers()
			);
		}
		return isset($this->apacheHeaders[$name])
			? $this->apacheHeaders[$name]
			: $default;
	}

	/**
	 * Returns a file array value ($_FILES)
	 *
	 * @access	public
	 * @param	string	$name		FILE key
	 * @return	array
	 */
	public function file($name)
	{
		return isset($_FILES[$name]) ? $_FILES[$name] : [];
	}

	/**
	 * Returns and Uploader form a FILE value key
	 *
	 * @access	public
	 * @param	string	$name		FILE key
	 * @param	array	$path		Path where the uploaded file will be saved
	 * @param	array	$mimes		Allowed mime types
	 * @param	bool	$replace	Replace existing files
	 * @return	Uploader
	 */
	public function uploader(
		$name,
		array $path = [],
		array $mimes = null,
		$replace = null
	) {
		return new Uploader($this->file($name), $path, $mimes, $replace);
	}
}
