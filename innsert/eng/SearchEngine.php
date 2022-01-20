<?php

namespace innsert\eng;

use \DateTime, \stdClass, innsert\db\DBMapper;

/**
 * Innsert PHP MVC Framework
 *
 * Search rows in mapper helper class, makes quering data received from the user a little easier
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class SearchEngine
{
	/**
	 * Mapper to query
	 *
	 * @access	public
	 * @var		DBMapper
	 */
	public $mapper;

	/**
	 * Values to search
	 *
	 * @access	public
	 * @var		array
	 */
	public $values;

	/**
	 * Mapper search params and rules
	 *
	 * @access	public
	 * @var		array
	 */
	public $params = [];

	/**
	 * Pagination instance
	 *
	 * @access	public
	 * @var		PaginationInterface
	 */
	public $pagination;

	/**
	 * Search results
	 *
	 * @access	public
	 * @var		Result
	 */
	public $result;

	/**
	 * Adiciones al final a datos recibidos
	 *
	 * @access  public
	 * @var     array
	 */
	public $additions = [
		'to-start' => ['append' => ' 00:00:00'],
		'to-end' => ['append' => ' 23:59:59'],
		'%' => ['append' => '%'],
		'%%' => ['append' => '%', 'prepend' => '%'],
	];

	/**
	 * Constructor
	 *
	 * Sets initial values and formats params with values
	 *
	 * @access	public
	 * @param	DBMapper			$mapper		DBMapper to be queried
	 * @param	array				$values		User data
	 * @param	array				$params		DBMapper params and rules
	 * @param	PaginationInterface	$configs	Optional Pagination
	 */
	public function __construct(
		DBMapper $mapper,
		array $values,
		array $params,
		PaginationInterface $pagination = null
	) {
		$this->mapper = $mapper;
		$this->values = $values;
		foreach ($params as $key => $value) {
			$field = is_int($key) ? $value : $key;
			$data = !is_array($value)
				? ['field' => $field, 'valueType' => '%%']
				: $value;
			if (!isset($data['field'])) {
				$data['field'] = $field;
			}
			$this->params[$field] = $data;
		}
		if (isset($pagination)) {
			$this->pagination = $pagination;
		}
		$this->build();
	}

	/**
	 * Formats special values and builds the search query
	 *
	 * @access  protected
	 */
	protected function build()
	{
		foreach ($this->values as $key => $value) {
			if (
				(is_array($value) && empty($value)) ||
				$value === '' ||
				!array_key_exists($key, $this->params)
			) {
				continue;
			}
			$data = (object) array_merge(
				[
					'type' => 'where',
					'operator' => 'like',
					'union' => 'and',
					'valueType' => 'default',
					'value' => $value,
				],
				$this->params[$key]
			);
			if (array_key_exists($data->valueType, $this->additions)) {
				if (isset($this->additions[$data->valueType]['prepend'])) {
					$data->value =
						$this->additions[$data->valueType]['prepend'] .
						$data->value;
				}
				if (isset($this->additions[$data->value]['append'])) {
					$data->value .=
						$this->additions[$data->valueType]['append'];
				}
			}
			$field = str_replace('-', '.', $data->field);
			switch ($data->type) {
				case 'where':
					$this->mapper->where(
						$field,
						$data->operator,
						$data->value,
						$data->union
					);
					break;
				case 'whereIn':
					$this->mapper->whereIn($field, $data->value, $data->union);
					break;
				case 'whereNotIn':
					$this->mapper->whereNotIn(
						$field,
						$data->value,
						$data->union
					);
					break;
				case 'order':
					$this->mapper->orderBy(["$field $data->value"]);
					break;
			}
		}
	}

	/**
	 * Executes search and pagination
	 *
	 * @access	public
	 * @return	SearchEngine
	 */
	public function search()
	{
		if (isset($this->pagination)) {
			$this->pagination->paginate($this->mapper);
			$this->result = $this->pagination->result();
			return $this;
		}
		$this->result = $this->mapper->find();
		return $this;
	}
}
