<?php

namespace innsert\lib;

use innsert\core\Forable;

/**
 * Innsert PHP MVC Framework
 *
 * Functions for collections
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class Collection extends Forable
{
	/**
	 * Collection items
	 *
	 * @access	public
	 * @var		array
	 */
	public $source;

	/**
	 * Constructor
	 *
	 * Sets the collection source
	 *
	 * @access	public
	 * @param	mixed	$data	Source array or object
	 * @throws	ParamMistypedException
	 */
	public function __construct($data)
	{
		if (is_array($data)) {
			$this->_items = $data;
		} else if (is_object($data)) {
			$this->_items = get_object_vars($data);
		} else {
			throw new ParamTypeException(gettype($data), 'array|object', $data);
		}
		$this->source = $this->_items;
	}

	/**
	 * Returns the given keys only
	 *
	 * @access	public
	 * @param	array	$only	Collection of keys to return
	 * @return	array
	 */
	public function only(array $only)
	{
		return array_intersect_key($this->_items, array_flip($only));
	}

	/**
	 * Returns the collection removing the given keys
	 *
	 * @access	public
	 * @param	array	$unsets		Collection of keys to remove
	 * @return	array
	 */
	public function clear(array $clear)
	{
		return array_diff_key($this->_items, array_flip($clear));
	}

	/**
	 * Returns a single value or a key => value pair form the collection
	 *
	 * @access	public
	 * @param	mixed	$column		Array key value to return
	 * @param	mixed	$index		Additional key valur to use as key
	 * @return	array
	 */
	public function column($column, $index = null)
	{
		$results = [];
		foreach ($this->_items as $key => $value) {
			$columnKey = (isset($index) && is_array($value) && isset($value[$index])) ? $value[$index] : $key;
			$results[$columnKey] = (!is_array($value) || !array_key_exists($column, $value)) ?
				'' : $value[$column];
		}
		return $results;
	}

	/**
	 * Sum values of a column
	 *
	 * @access	public
	 * @param	mixed		$column		Column key to sum
	 * @return 	decimal|int
	 */
	public function sum($column)
	{
		$values = $this->column($column);
		return array_sum($values);
	}
}