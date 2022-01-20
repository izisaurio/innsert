<?php

namespace innsert\lib;

/**
 * Innsert PHP MVC Framework
 *
 * Class to make CURL requests
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Curl
{
	/**
	 * Curl init resource
	 *
	 * @access	public
	 * @var		resource
	 */
	public $curl;

	/**
	 * Url to request
	 *
	 * @access	public
	 * @var		string
	 */
	public $url;

	/**
	 * Default params values
	 *
	 * @access	public
	 * @var		array
	 */
	public $defaultOptions = [
		CURLOPT_POST => true,
		CURLOPT_RETURNTRANSFER => true,
	];

	/**
	 * Http headers
	 *
	 * @access	public
	 * @var		array
	 */
	public $httpheaders = [];

	/**
	 * Form element values to send
	 *
	 * @access	public
	 * @var		array
	 */
	public $postfields = [];

	/**
	 * Send post fields as a query string
	 *
	 * @access	public
	 * @var		boolean
	 */
	public $postFieldsEncoded = true;

	/**
	 * Stores error if one exist
	 *
	 * @access	public
	 * @var		mixed
	 */
	public $error;

	/**
	 * Constructor
	 *
	 * Sets url, options and inits curl
	 *
	 * @access	public
	 * @param	string	$url		Url to request
	 * @param	array	$options	Curl options
	 */
	public function __construct($url, array $options = [])
	{
		$this->url = $url;
		$this->curl = curl_init($this->url);
		$this->defaultOptions = $options + $this->defaultOptions;
	}

	/**
	 * Adds an option to curl
	 *
	 * @access	public
	 * @param	int		$option		Option to add
	 * @param	mixed	$value		Option value
	 */
	public function option($option, $value)
	{
		$this->defaultOptions[$option] = $value;
	}

	/**
	 * Adds Curl header(s)
	 *
	 * @access	public
	 * @param	array|string	$values		Array of header values|Single value
	 * @return	Curl
	 */
	public function httpheader($values = null)
	{
		if (is_array($values)) {
			$this->httpheaders = $values;
		} else {
			$this->httpheaders[] = $values;
		}
		return $this;
	}

	/**
	 * Sets or adds a postfile value(s) to curl
	 *
	 * @access	public
	 * @param	array|string	$key	Array of values|Single value key
	 * @param	mixed			$value	Value for single key
	 * @return	Curl
	 */
	public function postfield($key, $value = null)
	{
		if (is_array($key)) {
			$this->postfields = $key;
		} else {
			$this->postfields[$key] = $value;
		}
		return $this;
	}

	/**
	 * Sets curl options, execs it, closes it and returns the result
	 *
	 * @access	public
	 * @return	mixed
	 */
	public function exec()
	{
		if (!empty($this->httpheaders)) {
			$this->defaultOptions[CURLOPT_HTTPHEADER] = $this->httpheaders;
		}
		if (!empty($this->postfields)) {
			$values = $this->postFieldsEncoded
				? http_build_query($this->postfields)
				: $this->postfields;
			$this->defaultOptions[CURLOPT_POSTFIELDS] = $values;
		}
		curl_setopt_array($this->curl, $this->defaultOptions);
		$response = curl_exec($this->curl);
		$this->error = curl_error($this->curl);
		curl_close($this->curl);
		return $response;
	}
}
