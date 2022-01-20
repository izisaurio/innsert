<?php

namespace innsert\core;

use innsert\lang\Lang;

/**
 * Innsert PHP MVC Framework
 *
 * Base class for error manager classes with message error file
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class ErrorManager extends Config
{
	/**
	 * Errors found in class validation method
	 *
	 * @access	public
	 * @var		array
	 */
	public $errors = [];

	/**
	 * Add error message to main array
	 *
	 * @access	public
	 * @param	string	$key		Config file error key
	 * @param	mixed	$values		Message params
	 */
	public function addError($key, $values)
	{
		$this->errors[] = $this->getError($key, $values);
	}

	/**
	 * Returns formatted error message
	 *
	 * @access	public
	 * @param	string	$key		Error message key
	 * @param	mixed	$values		Additional values
	 * @return	string
	 */
	public function getError($key, $values)
	{
		if (!is_array($values)) {
			$values = [$values];
		}
		return vsprintf($this->getMessage($key), $values);
	}

	/**
	 * Returns error message from file
	 *
	 * @access	protected
	 * @param	mixed	$key	Error message key in error file
	 * @return	string
	 */
	public function getMessage($key)
	{
		$message = $this->_items[$key];
		if (is_array($message)) {
			return $message[Lang::defaultInstance()->locale];
		}
		return $message;
	}
}
