<?php

namespace innsert\lib;

use innsert\core\Forable;

/**
 * Innsert PHP MVC Framework
 *
 * Getter of POST and GET request params
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class RequestParamValues extends Forable
{
	/**
	 * Source of values
	 *
	 * @access	public
	 * @var		array
	 */
	public $source;

	/**
	 * Storage for already fetched keys
	 *
	 * @access	public
	 * @var		array
	 */
	public $storage = [];

	/**
	 * Constructor
	 *
	 * Sets the source
	 *
	 * @access	public
	 * @param	array	$values		Source of values
	 */
	public function __construct(array $values)
	{
		$this->_items = $this->source = $values;
	}

	/**
	 * Adds an key => value pair to source
	 *
	 * @access	public
	 * @param	string	$name		Key
	 * @param	mixed	$value		Value
	 */
	public function set($name, $value)
	{
		$this->_items[$name] = $value;
		$this->source = $this->_items;
	}

	/**
	 * Returns the key value or the default given, before returning checks storage first
	 *
	 * @access	public
	 * @param	string	$name		Key value
	 * @param	mixed	$default	Default value if key not found
	 * @return	mixed
	 */
	public function value($name, $default = null)
	{
		if (array_key_exists($name, $this->storage)) {
			return $this->storage[$name];
		}
		if (!isset($this->_items[$name])) {
			return $default;
		}
		if (is_array($this->_items[$name])) {
			return $this->_items[$name];
		}
		$value = trim($this->_items[$name]);
		$this->storage[$name] = $value === '' ? $default : $value;
		return $this->storage[$name];
	}
}