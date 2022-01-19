<?php

namespace innsert\core;

use \Iterator;

/**
 * Innsert PHP MVC Framework
 *
 * Base class for iterable classes
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
abstract class Forable extends ArrayLike implements Iterator
{
	/**
	 * Arrays counter
	 *
	 * @access	private
	 * @var		int
	 */
	private $counter = 0;

	/**
	 * Resets array counter
	 *
	 * @access	public
	 */
	public function rewind()
	{
		$this->counter = 0;
	}

	/**
	 * Returns element in current position
	 *
	 * @access	public
	 */
	public function current()
	{
		return $this->_items[$this->counter];
	}

	/**
	 * Returns current position
	 *
	 * @access	public
	 */
	public function key()
	{
		return $this->counter;
	}

	/**
	 * Sets counter to next position
	 *
	 * @access	public
	 */
	public function next()
	{
		++$this->counter;
	}

	/**
	 * Returns if current position is valid
	 *
	 * @access	public
	 */
	public function valid()
	{
		return isset($this->_items[$this->counter]);
	}
}