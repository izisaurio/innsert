<?php

namespace innsert\mvc;

use innsert\val\Validation,
	innsert\lib\Collection;

/**
 * Innsert PHP MVC Framework
 *
 * Data model
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class Model
{
	/**
	 * Model id
	 *
	 * @access	public
	 * @var		mixed
	 */
	public $id;

	/**
	 * Model data validation
	 *
	 * @access	public
	 * @param	array	$rules		Model data validation rules
	 * @param	string	$join		String to join the errors
	 * @return	Model
	 * @throws	ControllerControledException
	 */
	public function validate(array $rules, $join = "\n")
	{
		$this->setDefaultValues($rules);
		$validation = new Validation($this, $rules);
		if (!$validation->check()) {
			throw new ControllerControlledException(join($join, $validation->errors));
		}
		return $this;
	}

	/**
	 * Sets model default values with rules given
	 *
	 * @access	public
	 * @param	array	$rules		Model rules
	 */
	public function setDefaultValues(array $rules = array())
	{
		foreach ($rules as $property => $rule) {
			if (is_array($rule) && array_key_exists('DEFAULT', $rule) && !isset($this->{$property})) {
				$this->{$property} = (new DefaultValue($this, $rule['DEFAULT']))->get();
			}
		}
	}

	/**
	 * Returns model properties as an array
	 *
	 * @access	public
	 * @return	array
	 */
	public function toArray()
	{
		return (new Collection($this))->source;
	}
}