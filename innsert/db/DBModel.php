<?php

namespace innsert\db;

use innsert\mvc\Model,
	innsert\mvc\ControllerControlledException;

/**
 * Innsert PHP MVC Framework
 *
 * Database mapper model
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class DBModel extends Model
{
	/**
	 * Mapper that created the model
	 *
	 * @access	public
	 * @var		DBMapper
	 */
	protected $_mapper;

	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	DBMapper	$mapper		Mapper that created the model
	 */
	public function __construct(DBMapper $mapper)
	{
		$this->_mapper = $mapper;
	}

	/**
	 * Returns parent mapper
	 *
	 * @access	public
	 * @return	DBMapper
	 */
	public function getMapper()
	{
		return $this->_mapper;
	}

	/**
	 * Validates current model values
	 *
	 * @access	public
	 * @param	array	$rules		Validations rules, if null check parent mapper rules
	 * @param	string	$join		Separator between errors
	 * @return	DbModel
	 * @throws	ControllerControledException
	 */
	public function validate(array $rules = null, $join = "\n")
	{
		if (!isset($rules)) {
			$rules = $this->_mapper->properties;
		}
		return parent::validate($rules, $join);
	}

	/**
	 * Save model, insert of update if id is set
	 *
	 * @access	public
	 * @param	bool	$lastId		Set id inserted if insertion
	 * @return	DBModel
	 * @throws	ControllerControlledException
	 */
	public function save($lastId = false)
	{
		try {
			$this->_mapper->save($this, $lastId);
		} catch (DatabaseStatementException $ex) {
			throw new ControllerControlledException($ex->getMessage());
		}
		return $this;
	}

	/**
	 * Deletes this model
	 *
	 * @access	public
	 * @throws	ControllerControlledException
	 */
	public function delete()
	{
		try {
			$this->_mapper->delete($this);
		} catch (DatabaseStatementException $ex) {
			throw new ControllerControlledException($ex->getMessage());
		}
	}

	/**
	 * Update foreign table with union data
	 *
	 * @access	public
	 * @param	DBUnion		$relation	DBUnion instance to update
	 * @param	array		$data		Data to store
	 * @return	DBModel
	 * @throws	ControllerControlledException
	 */
	public function updateUnion(DBUnion $relation, $data = array())
	{
		if (!$data || !is_array($data)) {
			$data = [];
		}
		try {
			$relation->updateUnion($this, $data);
		} catch (DatabaseStatementException $ex) {
			throw new ControllerControlledException($ex->getMessage());
		}
		return $this;
	}
}