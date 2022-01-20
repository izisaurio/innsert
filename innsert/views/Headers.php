<?php

namespace innsert\views;

use innsert\lib\Url, innsert\lib\Collection;

/**
 * Innsert PHP MVC Framework
 *
 * View headers manager
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Headers
{
	/**
	 * Elements collection
	 *
	 * @access	public
	 * @var		array
	 */
	public $items = [];

	/**
	 * Elements to add to view in string form
	 *
	 * @access	public
	 * @var		array
	 */
	public $results = [];

	/**
	 * Constructor
	 *
	 * Sets headers to write in a view
	 *
	 * @access	public
	 * @param	array	$headers	View header elements
	 */
	public function __construct(array $headers)
	{
		$this->items = $headers;
	}

	/**
	 * Prepend elements to headers collection
	 *
	 * @access	public
	 * @param	array	$headers	Headers to prepend
	 * @return	Headers
	 */
	public function prepend(array $headers)
	{
		$this->items = array_merge_recursive($headers, $this->items);
		return $this;
	}

	/**
	 * Appends elementos to headers collection
	 *
	 * @access	public
	 * @param	array	$headers	Headers to append
	 * @return	Headers
	 */
	public function append(array $headers)
	{
		$this->items = array_merge_recursive($this->items, $headers);
		return $this;
	}

	/**
	 * Parses headers array and writes the html elements in results array
	 *
	 * @access	public
	 * @return	Headers
	 */
	public function parse()
	{
		foreach ($this->items as $type => $values) {
			if (!is_array($values)) {
				$values = [$values];
			}
			if (!isset($this->results[$type])) {
				$this->results[$type] = [];
			}
			foreach ($values as $value) {
				switch ($type) {
					case 'css':
						if (strpos($value, ':') === false) {
							$value = (new Url([$value]))->make();
						}
						$this->results[$type][] =
							'<link rel="stylesheet" href="' . $value . '" />';
						break;

					case 'js':
						if (strpos($value, ':') === false) {
							$value = (new Url([$value]))->make();
						}
						$this->results[$type][] =
							'<script src="' . $value . '" ></script>';
						break;

					case 'title':
						$this->results[$type][] =
							'<title>' . $value . '</title>';
						break;

					case 'meta':
						$attrs = '';
						foreach ($value as $name => $attr) {
							$attrs .= $name . '="' . $attr . '" ';
						}
						$this->results[$type][] = '<meta ' . $attrs . '/>';
						break;

					case 'icon':
						if (strpos($value, ':') === false) {
							$value = (new Url([$value]))->make();
						}
						$this->results[$type][] =
							'<link rel="shortcut icon" href="' .
							$value .
							'" />';
						break;
				}
			}
		}
		return $this;
	}

	/**
	 * Returns string with the written headers
	 *
	 * @access	public
	 * @param	array	$only	If you want to write only some some types of elements
	 * @return	string
	 */
	public function get(array $only = [])
	{
		$tags = '';
		foreach ($this->results as $type => $result) {
			if (!empty($only) && !in_array($type, $only)) {
				continue;
			}
			foreach ($result as $tag) {
				$tags .= "{$tag}\n";
			}
		}
		return $tags;
	}
}
