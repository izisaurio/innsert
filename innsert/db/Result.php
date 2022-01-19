<?php

namespace innsert\db;

use innsert\mvc\ControllerControlledException,
	innsert\lib\Collection,
	innsert\lang\Lang;

/**
 * Innsert PHP MVC Framework
 *
 * Gets search query raw data and transforms it to models
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Result
{
	/**
	 * Results form query
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $source;

	/**
	 * Mapper that created the results
	 *
	 * @access	protected
	 * @var		DBMapper
	 */
	protected $mapper;

	/**
	 * Constructor
	 *
	 * Sets source and mapper
	 *
	 * @access	public
	 * @param	array		$source		Query results raw
	 * @param	DBMapper	$mapper		Mapper that created  the results
	 */
	public function __construct(array $source, DBMapper $mapper)
	{
		$this->source = $source;
		$this->mapper = $mapper;
	}

	/**
	 * Returns results source
	 *
	 * @access	public
	 * @return	array
	 */
	public function source()
	{
		return $this->source;
	}

	/**
	 * Returns results as a model collection
	 *
	 * @access	public
	 * @return	array
	 */
	public function all()
	{
		$results = [];
		foreach ($this->source as $row) {
			$new = $this->mapper->getModel();
			foreach ($row as $property => $value) {
				$new->$property = $value;
			}
			$results[] = $new;
		}
		return $results;
	}

	/**
	 * Returns a single column
	 *
	 * @access	public
	 * @param	string	$column		Column to return
	 * @return	array
	 */
	public function column($column)
	{
		return (new Collection($this->source))->column($column);
	}

	/**
	 * Returns first to columns as an associative array
	 *
	 * @access	public
	 * @return	array
	 */
	public function assoc()
	{
		if (empty($this->source)) {
			return [];
		}
		list($key, $value) = array_keys($this->source[0]);
		return (new Collection($this->source))->column($value, $key);
	}

	/**
	 * Returns first result as model
	 *
	 * @access	public
	 * @return	DBModel
	 */
	public function first()
	{
		$all = $this->all();
		return isset($all[0]) ? $all[0] : null;
	}

	/**
	 * Returns first result, if fails throws exception
	 *
	 * @access	public
	 * @param	string	$error		Error message
	 * @return	DBModel
	 */
	public function firstOrFail($error = null)
	{
		$first = $this->first();
		if (!$first) {
			$message = isset($error) ? $error : Lang::defaultInstance()->get('error');
			throw new ControllerControlledException($message);
		}
		return $first;
	}
}