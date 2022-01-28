<?php

namespace innsert\db;

use innsert\lib\Collection, innsert\mvc\ControllerControlledException;

/**
 * Innsert PHP MVC Framework
 *
 * Database table mapper
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
abstract class DBMapper extends SqlSentence
{
	/**
	 * DNInterface instance
	 *
	 * @access	public
	 * @var		DBInterface
	 */
	public $db;

	/**
	 * Id column name
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $primary = 'id';

	/**
	 * Table properties with validations
	 *
	 * @access	public
	 * @var		array
	 */
	public $properties = [];

	/**
	 * Table unions with foreign tables
	 *
	 * @access	public
	 * @var		array
	 */
	public $unions = [];

	/**
	 * Constructor
	 *
	 * Sets the DBInterface instance or picks defautl one
	 *
	 * @access	public
	 * @param	DBInterface		$database	Database instance
	 */
	public function __construct(DBInterface $database = null)
	{
		$this->db = isset($database) ? $database : DB::defaultInstance();
		if (!isset($this->table)) {
			$this->table = substr(strrchr(get_class($this), '\\'), 1);
		}
	}

	/**
	 * Selection columns for search queries
	 *
	 * @access	public
	 * @param	mixed	$select		Fileds to select
	 * @param	array	$rules		Foreign table unions
	 * @return	SqlSentence
	 */
	public function select($select, array $rules = [])
	{
		if (empty($rules)) {
			if (empty($this->unions)) {
				$this->unions = (new Collection($this->properties))->column(
					'UNION'
				);
			}
			$rules = $this->unions;
		}
		return parent::select($select, $rules);
	}

	/**
	 * Returns a search query Result object
	 *
	 * @access	public
	 * @param	Params	$params		Params to add to search sentence
	 * @return	Result
	 */
	public function find(Params $params = null)
	{
		if (empty($this->select)) {
			$this->select('*');
		}
		return new Result(
			$this->db->search($this->buildSelect(), $params),
			$this
		);
	}

	/**
	 * Returns a single model by id column
	 *
	 * @access	public
	 * @param	mixed	$id		Column id
	 * @return	DBModel
	 */
	public function findId($id)
	{
		return $this->where($this->primary, $id)
			->limit(1)
			->find()
			->first();
	}

	/**
	 * Returns a model by id, if null throw exception
	 *
	 * @access	public
	 * @param	mixed	$id		Column id
	 * @param	string	$error	Exception error
	 * @return	DBModel
	 */
	public function findIdOrFail($id, $error = null)
	{
		return $this->where($this->primary, $id)
			->limit(1)
			->find()
			->firstOrFail($error);
	}

	/**
	 * Inserts a model to database
	 *
	 * @access	public
	 * @param	DBModel	$model		Model to insert
	 * @param	bool	$lastId		Set last id to inserted model flag
	 * @throws	PropertyNotFoundException
	 */
	public function insert(DBModel $model, $lastId = false)
	{
		$this->reset();
		$values = $model->toArray();
		$params = new Params();
		foreach ($this->properties as $property => $options) {
			if (
				!array_key_exists($property, $values) &&
				$property != $this->primary
			) {
				throw new PropertyNotFoundException($this->table, $property);
			}
			$class = is_array($options) ? $options['CLASS'] : $options;
			$params->add($class, $values[$property]);
		}
		$this->db->execute(
			$this->buildInsert(array_keys($this->properties)),
			$params
		);
		if ($lastId) {
			$model->id = $this->db->lastId();
		}
	}

	/**
	 * Updates a model
	 *
	 * @access	public
	 * @param	DBModel	$model	Model to update
	 */
	public function update(DBModel $model)
	{
		$this->reset();
		$this->updateAll($model->toArray());
	}

	/**
	 * Updates values of current mapper conditionals
	 *
	 * @access	public
	 * @param	array	$data	Data to update
	 */
	public function updateAll(array $data)
	{
		$properties = array_intersect(
			array_keys($this->properties),
			array_keys($data)
		);
		$params = new Params();
		foreach ($properties as $property) {
			$options = $this->properties[$property];
			$class = is_array($options) ? $options['CLASS'] : $options;
			$params->add($class, $data[$property]);
		}
		if (isset($data[$this->primary])) {
			$params->add('INT', $data[$this->primary]);
			$this->where($this->primary, '=', '?');
		}
		$this->db->execute($this->buildUpdate($properties), $params);
	}

	/**
	 * Saves a model, insert or update if id is present
	 *
	 * @access	public
	 * @param	DBModel	$model		Model to save
	 * @param	bool	$lastId		Set last id to model flag when insertion
	 */
	public function save(DBModel $model, $lastId = false)
	{
		if (isset($model->{$this->primary})) {
			$this->update($model);
		} else {
			$this->insert($model, $lastId);
		}
	}

	/**
	 * Delete rows on current mapper conditionals
	 *
	 * @access	public
	 */
	public function deleteAll(Params $params = null)
	{
		$this->db->execute($this->buildDelete(), $params);
	}

	/**
	 * Deletes a model row
	 *
	 * @access	public
	 * @param	DBModel	$model	Model to delete
	 */
	public function delete(DBModel $model)
	{
		$this->where('id', '=', '?');
		$params = new Params(['INT' => $model->id]);
		$this->db->execute($this->buildDelete(), $params);
	}

	/**
	 * Counts results of current mapper conditionals
	 *
	 * @access	public
	 * @param	Params	$params		Params to add to sentence
	 * @return	int
	 */
	public function count(Params $params = null)
	{
		$select = empty($this->groupBy)
			? 'COUNT( 1 ) AS count'
			: "COUNT(DISTINCT {$this->table}.{$this->primary}) AS count";
		$this->groupBy = [];
		$counted = $this->select($select)
			->limit(1)
			->find($params)
			->first();
		return isset($counted) ? $counted->count : 0;
	}

	/**
	 * Returns if a current mappers conditionals exist
	 *
	 * @access	public
	 * @param	Params	$params		Params to add to sentence
	 * @return	bool
	 */
	public function exists(Params $params = null)
	{
		$exists =
			'SELECT EXISTS (' .
			$this->select('1')->buildSelect() .
			') AS FOUND';
		return (new Result(
			$this->db->search($exists, $params),
			$this
		))->source()[0]['FOUND'];
	}

	/**
	 * If current mapper conditions exists throw exception
	 *
	 * @access	public
	 * @param	string	$error		Error message
	 * @param	Params	$params		Params to add to sentence
	 * @throws	ControllerControlledException
	 */
	public function failIfExists($error, Params $params = null)
	{
		if ($this->exists($params)) {
			throw new ControllerControlledException($error);
		}
	}

	/**
	 * If current mapper conditions do no exists throw exception
	 *
	 * @access	public
	 * @param	string	$error		Error message
	 * @param	Params	$params		Params to add to sentence
	 * @throws	ControllerControlledException
	 */
	public function failIfNotExists($error, Params $params = null)
	{
		if (!$this->exists($params)) {
			throw new ControllerControlledException($error);
		}
	}

	/**
	 * Returns current mapper key column
	 *
	 * @access	public
	 * @return	string
	 */
	public function getPrimary()
	{
		return $this->primary;
	}

	/**
	 * Cleans query values with $db->clean()
	 *
	 * @access	public
	 * @param	mixed	$value		Value to clean
	 * @return	mixed
	 */
	public function clean($value)
	{
		return !is_numeric($value) && $value !== '?'
			? $this->db->clean($value)
			: $value;
	}

	/**
	 * Returns a new instance with same table and DBInterface
	 *
	 * @access	protected
	 * @return	SqlSentence
	 */
	protected function newSelf()
	{
		$self = new static();
		$self->db = $this->db;
		$self->table = $this->table;
		return $self;
	}

	/**
	 * Returns current mapper model, generic DBModel by default
	 *
	 * @access	public
	 * @return	DBModel
	 */
	public function getModel()
	{
		return new DBModel($this);
	}
}
