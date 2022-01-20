<?php

namespace innsert\core;

use \Arrayaccess;

/**
 * Innsert PHP MVC Framework
 *
 * Base class for array accessed classes
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
abstract class ArrayLike implements Arrayaccess
{
	/**
	 * Main array of the class
	 *
	 * @access	public
	 * @var		array
	 */
	public $_items = [];

	/**
	 * Set item
	 *
	 * @access	public
	 * @param	mixed	$key	Array key
	 * @param	mixed	$value	Array value
	 */
	public function offsetSet($key, $value)
	{
		if (is_null($key)) {
			$this->_items[] = $value;
		} else {
			$this->_items[$key] = $value;
		}
	}

	/**
	 * Array key exists
	 *
	 * @access	public
	 * @param	mixed	$key	Array key
	 * @return	bool
	 */
	public function offsetExists($key)
	{
		return isset($this->_items[$key]);
	}

	/**
	 * Unset array key
	 *
	 * @access	public
	 * @param	mixed	$key	Array key
	 */
	public function offsetUnset($key)
	{
		unset($this->_items[$key]);
	}

	/**
	 * Returns a key value
	 *
	 * @access	public
	 * @param	mixed	$key	Array key
	 * @return	mixed
	 */
	public function offsetGet($key)
	{
		return isset($this->_items[$key]) ? $this->_items[$key] : null;
	}
}
