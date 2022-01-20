<?php

namespace innsert\lib;

/**
 * Innsert PHP MVC Framework
 *
 * Helper class for creating urls
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class Url
{
	/**
	 * Url parts
	 *
	 * @access	public
	 * @var		array
	 */
	public $parts = [];

	/**
	 * Query string of this instance
	 *
	 * @access	public
	 * @var		array
	 */
	public $params = [];

	/**
	 * Hashtag to include in this instance
	 *
	 * @access	public
	 * @var		string
	 */
	public $hashtag;

	/**
	 * Current domain
	 *
	 * @access	public
	 * @var		string
	 */
	public $domain;

	/**
	 * Constructor
	 *
	 * Sets base url
	 *
	 * @access	public
	 * @param	mixed	$parts[]	Url parts
	 */
	public function __construct($parts = null)
	{
		if (!isset($parts)) {
			$this->parts = [];
		} else {
			$this->parts = !is_array($parts) ? func_get_args() : $parts;
		}
	}

	/**
	 * Adds element(s) to base url
	 *
	 * @access	public
	 * @param	string[]	$parts	Element(s) to add
	 * @return	Url
	 */
	public function add()
	{
		$this->parts = array_merge($this->parts, func_get_args());
		return $this;
	}

	/**
	 * Sets base url as current url
	 *
	 * @access	public
	 * @return	Url
	 */
	public function current()
	{
		return Request::defaultInstance()->router->url;
	}

	/**
	 * Sets base url as current controller->action
	 *
	 * @access	public
	 * @return	Url
	 */
	public function action()
	{
		$request = Request::defaultInstance();
		$this->parts = array_merge($request->router->controller, [
			$request->router->action,
		]);
		return $this;
	}

	/**
	 * Adds query string params to base url
	 *
	 * @access	public
	 * @param	array	$query	Params to add
	 * @return	Url
	 */
	public function params(array $query)
	{
		$this->params = $query;
		return $this;
	}

	/**
	 * Adds domain to base url
	 *
	 * @access	public
	 * @param	string	$domain	Domain to add to base url
	 * @return	Url
	 */
	public function domain($domain)
	{
		$this->domain = $domain;
		return $this;
	}

	/**
	 * Adds current domain to base url
	 *
	 * @access	public
	 * @return	Url
	 */
	public function myDomain()
	{
		return $this->domain(DOMAIN);
	}

	/**
	 * Adds hashtag target to base url
	 *
	 * @access	public
	 * @param	string	$hashtag	Hashtag target value
	 * @return	Url
	 */
	public function tag($hashtag)
	{
		$this->hashtag = $hashtag;
		return $this;
	}

	/**
	 * Transforms base url parts to url safe characters
	 *
	 * @access	public
	 * @return	Url
	 */
	public function friendly()
	{
		$unwanted = [
			'á' => 'a',
			'é' => 'e',
			'í' => 'i',
			'ó' => 'o',
			'ú' => 'u',
			'Á' => 'A',
			'É' => 'E',
			'Í' => 'I',
			'Ó' => 'O',
			'Ú' => 'U',
			'ñ' => 'n',
			'Ñ' => 'N',
			' ' => '-',
			'!' => '',
			'(' => '',
			')' => '',
			'?' => '',
			',' => '',
			'.' => '',
			';' => '',
			'/' => '',
			':' => '',
			'¿' => '',
			'¡' => '',
			'=' => '',
			'’' => '',
			"'" => '',
			'"' => '',
		];
		foreach ($this->parts as &$part) {
			$part = strtolower(strtr($part, $unwanted));
		}
		return $this;
	}

	/**
	 * Returns the full url
	 *
	 * @access	public
	 * @return	string
	 */
	public function make()
	{
		$link = join(US, $this->parts);
		if (REWRITES) {
			$base = PATH;
		} else {
			$base = strpos($link, '.') === false ? PATH . SCRIPT . US : PATH;
		}
		if (isset($this->domain)) {
			$base = $this->domain . $base;
		}
		$end = isset($this->hashtag) ? '#' . $this->hashtag : '';
		if (!empty($this->params)) {
			return $base . $link . '?' . http_build_query($this->params) . $end;
		}
		return $base . $link . $end;
	}

	/**
	 * Returns url with urlencode
	 *
	 * @access	public
	 * @return	string
	 */
	public function encoded()
	{
		return urlencode($this->make());
	}

	/**
	 * toString
	 *
	 * @access	public
	 * @return	string
	 */
	public function __toString()
	{
		return $this->make();
	}
}
