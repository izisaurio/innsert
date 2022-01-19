<?php

namespace innsert\db;

use \stdClass;

/**
 * Innsert PHP MVC Framework
 *
 * Represents a many to many table
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class DBUnion extends SqlSentence
{
	/**
	 * DNInterface instance
	 *
	 * @access	public
	 * @var		DBInterface
	 */
	public $db;

	/**
	 * Collection of the two tables and their keys
	 *
	 * @access	public
	 * @var		array
	 */
	public $properties;

	/**
	 * Constructor
	 *
	 * Sets the Database interface, if null uses default
	 *
	 * @access	public
	 * @param	DBInterface		$database		El objeto de base de datos
	 */
	public function __construct(DBInterface $database = null)
	{
		$this->db = isset($database) ? $database : DB::defaultInstance();
		if (!isset($this->table)) {
			$this->table = substr(strrchr(get_class($this), '\\'), 1);
		}
	}

	/**
	 * Exceutes relationship update
	 *
	 * @access	public
	 * @param	DBModel	$model		Model to update
	 * @param	array	$data		Values to register for the model
	 */
	public function updateUnion(DBModel $model, array $values)
	{
		$data = $this->getData($model);
		$this->where($data->this->key, $model->id);
		$this->db->execute($this->buildDelete());
		$this->db->prepareStatement($this->buildInsert([$data->this->key, $data->union->key]));
		foreach ($values as $value) {
			$params = new Params;
			$params->add('INT', $model->id);
			$params->add('INT', $value);
			$this->db->bindParams($params);
			$this->db->executeStatement();
		}
	}

	/**
	 * Returns all model related to the one given
	 *
	 * @access	public
	 * @param	DBModel	$model		Results will be related to this model
	 * @return	array
	 */
	public function findUnion(DBModel $model)
	{
		$data = $this->getData($model);
		$this->join($this->table, $data->union->key, '=', "{$data->union->table}.{$data->union->id}")
			->where($data->this->key, $model->id);
		return (new Result($this->db->search($this->buildSelect()), $model->getMapper()))->all();
	}

	/**
	 * Returns all model ids related to the one given
	 *
	 * @access	public
	 * @param	DBModel	$model		Results will be related to this model
	 * @return	array
	 */
	public function findUnionIds(DBModel $model)
	{
		$data = $this->getData($model);
		$this->select([$data->union->key])
			->where($data->this->key, $model->id);
		return (new Result($this->db->search($this->buildSelect()), $model->getMapper()))
			->column($data->union->key);
	}

	/**
	 * Searches this union raw data
	 *
	 * @access	public
	 * @return	array
	 */
	public function findAll()
	{
		return $this->db->search($this->select(['*'])->buildSelect());
	}

	/**
	 * Deletes a single value from this union
	 *
	 * @access	public
	 * @param	DBModel	$model		A value will be deleted from this model
	 * @param	mixed	$value		Value to delete
	 */
	public function deleteValue(DBModel $model, $value)
	{
		$data = $this->getData($model);
		$this->where($data->this->key, $model->id)->where($data->union->key, $value);
		$this->db->execute($this->buildDelete());
	}

	/**
	 * Retunrs ordered properties
	 *
	 * @access	protected
	 * @param	DBModel	$model		Model to use
	 * @return	stdClass
	 */
	protected function getData(DBModel $model)
	{
		$table = $model->getMapper()->table;
		foreach ($this->properties as $key => $values) {
			if ($key != $table) {
				$data = new stdClass;
				$data->this = (object)[
					'table'	=>	$table,
					'key'	=>	$this->properties[$table]['key'],
					'id'	=>	$this->properties[$table]['id']
				];
				$data->union = (object)[
					'table'	=>	$key,
					'key'	=>	$values['key'],
					'id'	=>	$values['id']
				];
				return $data;
			}
		}
	}

	/**
	 * Cleans query values with $db->clean()
	 *
	 * @access	protected
	 * @param	mixed	$value		Value to clean
	 * @return	mixed
	 */
	protected function clean($value)
	{
		return (!is_numeric($value) && $value !== '?') ? $this->db->clean($value) : $value;
	}
}