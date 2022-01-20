<?php

namespace innsert\views;

/**
 * Innsert PHP MVC Framework
 *
 * Master layout view
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Master extends View
{
	/**
	 * Headers collection
	 *
	 * @access	public
	 * @var		array
	 */
	public $headers = [];

	/**
	 * Adds a header to collection
	 *
	 * @access	public
	 * @param	array	$headers	Header elements
	 */
	public function addHeaders(array $headers)
	{
		$this->headers = array_merge($this->headers, $headers);
	}

	/**
	 * Returns the headers in this Master view as html text
	 *
	 * @access	public
	 * @param	array	$headers	Master view headers
	 * @param	array	$only		If you want to write only some some types of elements
	 * @return	string
	 */
	public function getAllHeaders(array $headers = [], array $only = [])
	{
		return (new Headers($this->headers))
			->prepend($headers)
			->parse()
			->get($only);
	}
}
