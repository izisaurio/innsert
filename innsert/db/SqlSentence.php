<?php

namespace innsert\db;

use \Closure;

/**
 * Innsert PHP MVC Framework
 *
 * Class to create sql sentences
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class SqlSentence
{
	/**
	 * Table name of sentence
	 *
	 * @access	public
	 * @var		string
	 */
	public $table;

	/**
	 * Alias de la tablas
	 *
	 * @access	public
	 * @var		string
	 */
	public $alias;

	/**
	 * Collection of columns
	 *
	 * @access	public
	 * @var		array
	 */
	public $select = [];

	/**
	 * Collection of conditionals
	 *
	 * @access	public
	 * @var		array
	 */
	public $where = [];

	/**
	 * Collection joins
	 *
	 * @access	public
	 * @var		array
	 */
	public $join = [];

	/**
	 * Collection of conditionals for a join
	 *
	 * @access	public
	 * @var		array
	 */
	public $on = [];

	/**
	 * Collection order conditions
	 *
	 * @access	public
	 * @var		array
	 */
	public $orderBy = [];

	/**
	 * Collection of group conditions
	 *
	 * @access	public
	 * @var		array
	 */
	public $groupBy = [];

	/**
	 * Collection of having conditions
	 *
	 * @access	public
	 * @var		array
	 */
	public $having = [];

	/**
	 * Query result limit
	 *
	 * @access	public
	 * @var		string
	 */
	public $limit = '';

	/**
	 * Index in search query
	 *
	 * @access	public
	 * @var		string
	 */
	public $index = '';

	/**
	 * Resets all sentence values
	 *
	 * @access	public
	 * @return	mixed
	 */
	public function reset()
	{
		$this->select = $this->where = $this->join = $this->order = $this->group = $this->having = [];
		$this->alias = $this->limit = '';
		return $this;
	}

	/**
	 * Alias of sub select
	 *
	 * @access	public
	 * @param	string		$alias		Search alias
	 * @return	mixed
	 */
	public function sub($alias)
	{
		$this->alias = $alias;
		return $this;
	}

	/**
	 * Select table columns
	 *
	 * @access	public
	 * @param	mixed		$select		Columns
	 * @param	array		$rules		Union rules in select
	 * @return	mixed
	 */
	public function select($select, array $rules = [])
	{
		if (!is_array($select)) {
			$this->select[] = $select;
			return $this;
		}
		foreach ($select as $key => $column) {
			if (!is_int($key) && !empty($rules) && isset($rules[$key])) {
				list($foreignTable, $foreignId) = explode('|', $rules[$key]);
				$this->addForeignColumns(
					$key,
					$column,
					$foreignTable,
					$foreignId
				);
				continue;
			}
			if ($column instanceof SqlSentence) {
				$this->select[] = "({$column->buildSelect()}) AS {$column->alias}";
				continue;
			}
			$this->select[] = is_array($column)
				? $column[0]
				: $this->prepareColumn($column);
		}
		return $this;
	}

	/**
	 * Adds a foreign key value to select
	 *
	 * @access	protected
	 * @param	string		$column			Table key column
	 * @param	mixed		$selects		Foreign table column(s)
	 * @param	string		$foreignTable	Foreign table name
	 * @param	string		$foreignId		Foreign table key
	 * @return	mixed
	 */
	protected function addForeignColumns(
		$column,
		$selects,
		$foreignTable,
		$foreignId
	) {
		if (!is_array($selects)) {
			$selects = [$selects];
		}
		$this->innerJoin($foreignTable, $foreignId, '=', $column);
		foreach ($selects as $select) {
			$this->select[] =
				strpos($select, ' ') === false
					? "{$foreignTable}.{$select} AS {$column}_{$select}"
					: "{$foreignTable}.{$select}";
		}
		return $this;
	}

	/**
	 * Adds a raw where
	 *
	 * @access	protected
	 * @param	string			$where		Where operation
	 * @param	string			$type		Where union type (and, or)
	 * @return	mixed
	 */
	protected function whereHandler($where, $type = 'AND')
	{
		$where = empty($this->where) ? $where : "{$type} {$where}";
		$this->where[] = $where;
		return $this;
	}

	/**
	 * Add a where operation to sentence
	 *
	 * Add a closure as first param for nested wheres
	 *
	 * @access	public
	 * @param	string/Closure	$compare	Column or value to compare|Closure for nested wheres
	 * @param	mixed			$operator	Where operator
	 * @param	mixed			$to			Value to compare
	 * @param	string			$type		Where union type (and, or)
	 * @return	mixed
	 */
	public function where($compare, $operator = null, $to = null, $type = 'AND')
	{
		if ($compare instanceof Closure) {
			$sentence = $this->newSelf();
			$compare($sentence);
			$this->whereHandler("({$this->buildWhere($sentence)})", $type);
			return $this;
		}
		if (!isset($to)) {
			$to = $operator;
			$operator = '=';
		}
		$to = is_array($to) ? $to[0] : $this->quote($to);
		$compare = is_array($compare)
			? $compare[0]
			: $this->prepareColumn($compare);
		return $this->whereHandler("{$compare} {$operator} {$to}", $type);
	}

	/**
	 * Builds the whole where of a given sentence
	 *
	 * @access	protected
	 * @param	SqlSentence		$sentence	Sentence to build where
	 * @return	string
	 */
	protected function buildWhere(SqlSentence $sentence)
	{
		return join(' ', $sentence->where);
	}

	/**
	 * Adds a where with "And" type
	 *
	 * Add a closure as first param for nested wheres
	 *
	 * @access	public
	 * @param	string/Closure	$compare	Column or value to compare|Closure for nested wheres
	 * @param	mixed			$operator	Where operator
	 * @param	mixed			$to			Value to compare
	 * @return	mixed
	 */
	public function andWhere($compare, $operator = null, $to = null)
	{
		return $this->where($compare, $operator, $to);
	}

	/**
	 * Adds a where with "Or" type
	 *
	 * Add a closure as first param for nested wheres
	 *
	 * @access	public
	 * @param	string/Closure	$compare	Column or value to compare|Closure for nested wheres
	 * @param	mixed			$operator	Where operator
	 * @param	mixed			$to			Value to compare
	 * @return	mixed
	 */
	public function orWhere($compare, $operator = null, $to = null)
	{
		return $this->where($compare, $operator, $to, 'OR');
	}

	/**
	 * Adss a wherein to sentence
	 *
	 * @access	public
	 * @param	string			$compare	Column or value to compare
	 * @param	array			$values		Values
	 * @param	string			$type		Where union type (and, or)
	 * @param	string			$operator	Operator
	 * @return	mixed
	 */
	public function whereIn(
		$compare,
		array $values,
		$type = 'AND',
		$operator = 'IN'
	) {
		if (empty($values)) {
			return $this;
		}
		$compare = is_array($compare)
			? $compare[0]
			: $this->prepareColumn($compare);
		$data = join(',', $this->quote($values));
		return $this->whereHandler("{$compare} {$operator} ({$data})", $type);
	}

	/**
	 * Adss a "where in" to sentence of type "Or"
	 *
	 * @access	public
	 * @param	string			$compare	Column or value to compare
	 * @param	array			$values		Values
	 * @return	mixed
	 */
	public function orWhereIn($compare, array $values)
	{
		return $this->whereIn($compare, $values, 'OR');
	}

	/**
	 * Adds a "where not in" to sentence
	 *
	 * @access	public
	 * @param	string			$compare	Column or value to compare
	 * @param	array			$values		Values
	 * @param	string			$type		Where union type (and, or)
	 * @return	mixed
	 */
	public function whereNotIn($compare, $values, $type = 'AND')
	{
		return $this->whereIn($compare, $values, $type, 'NOT IN');
	}

	/**
	 * Adds a "where not in" to sentence of type "Or"
	 *
	 * @access	public
	 * @param	string			$compare	Column or value to compare
	 * @param	array			$values		Values
	 * @return	mixed
	 */
	public function orWhereNotIn($compare, $values)
	{
		return $this->whereNotIn($compare, $values, 'OR');
	}

	/**
	 * Adds a "where null" to sentence
	 *
	 * @access	public
	 * @param	string			$compare	Column or value to compare
	 * @param	string			$type		Where union type (and, or)
	 * @return	mixed
	 */
	public function whereNull($compare, $type = 'AND')
	{
		return $this->where($compare, 'IS', ['NULL'], $type);
	}

	/**
	 * Adds a "where null" to sentence of type "Or"
	 *
	 * @access	public
	 * @param	string			$compare	Column or value to compare
	 * @return	mixed
	 */
	public function orWhereNull($compare)
	{
		return $this->where($compare, 'IS', ['NULL'], 'OR');
	}

	/**
	 * Adds a "where not null" to sentence
	 *
	 * @access	public
	 * @param	string			$compare	Column or value to compare
	 * @param	string			$type		Where union type (and, or)
	 * @return	mixed
	 */
	public function whereNotNull($compare, $type = 'AND')
	{
		return $this->where($compare, 'IS NOT', ['NULL'], $type);
	}

	/**
	 * Adds a "where not null" to sentence of type "Or"
	 *
	 * @access	public
	 * @param	string			$compare	Column or value to compare
	 * @return	mixed
	 */
	public function orWhereNotNull($compare)
	{
		return $this->where($compare, 'IS NOT', ['NULL'], 'OR');
	}

	/**
	 * Adds a "where between" to sentence
	 *
	 * @access	public
	 * @param	string			$compare	Column or value to compare
	 * @param	string			$first		First value of range
	 * @param	string			$second		Second value of range
	 * @param	string			$type		Where union type (and, or)
	 * @return	mixed
	 */
	public function whereBetween($compare, $first, $second, $type = 'AND')
	{
		$compare = is_array($compare)
			? $compare[0]
			: $this->prepareColumn($compare);
		$first = is_array($first) ? $first[0] : $this->quote($first);
		$second = is_array($first) ? $second[0] : $this->quote($second);
		return $this->whereHandler(
			"{$compare} BETWEEN {$first} AND {$second}",
			$type
		);
	}

	/**
	 * Adds a "where between" to sentence of type "Or"
	 *
	 * @access	public
	 * @param	string			$compare	Campo o valor a comparar
	 * @param	string			$first		Primer valor del rango
	 * @param	string			$second		Segundo valor del rango
	 * @return	mixed
	 */
	public function orWhereBetween($compare, $first, $second)
	{
		return $this->whereBetween($compare, $first, $second, 'OR');
	}

	/**
	 * Adds a join between tables
	 *
	 * @access	public
	 * @param	string				$join			Foreign table
	 * @param	string|Clousure		$joinColumn		Foreign table key|Closure for nested joins
	 * @param	string				$operator		Operator
	 * @param	string				$tableColumn	This table key
	 * @param	string				$type			Type of join
	 * @return	mixed
	 */
	public function join(
		$join,
		$joinColumn,
		$operator = null,
		$tableColumn = null,
		$type = 'INNER'
	) {
		if ($joinColumn instanceof Closure) {
			$sentence = $this->newSelf();
			$joinColumn($sentence);
			$this->join[] = "{$type} JOIN {$join} ON {$this->buildOn(
				$sentence
			)}";
			return $this;
		}
		$this->join[] = "{$type} JOIN {$join} ON {$this->addOn(
			$join,
			$joinColumn,
			$operator,
			$tableColumn
		)}";
		return $this;
	}

	/**
	 * Adds a join between tables of type "Inner"
	 *
	 * @access	public
	 * @param	string				$join			Foreign table
	 * @param	string|Clousure		$joinColumn		Foreign table key|Closure for nested joins
	 * @param	string				$operator		Operator
	 * @param	string				$tableColumn	This table key
	 * @return	mixed
	 */
	public function innerJoin(
		$join,
		$joinColumn,
		$operator = null,
		$tableColumn = null
	) {
		return $this->join($join, $joinColumn, $operator, $tableColumn);
	}

	/**
	 * Adds a join between tables of type "Left"
	 *
	 * @access	public
	 * @param	string				$join			Foreign table
	 * @param	string|Clousure		$joinColumn		Foreign table key|Closure for nested joins
	 * @param	string				$operator		Operator
	 * @param	string				$tableColumn	This table key
	 * @return	mixed
	 */
	public function leftJoin(
		$join,
		$joinColumn,
		$operator = null,
		$tableColumn = null
	) {
		return $this->join($join, $joinColumn, $operator, $tableColumn, 'LEFT');
	}

	/**
	 * Adds a join between tables of type "Right"
	 *
	 * @access	public
	 * @param	string				$join			Foreign table
	 * @param	string|Clousure		$joinColumn		Foreign table key|Closure for nested joins
	 * @param	string				$operator		Operator
	 * @param	string				$tableColumn	This table key
	 * @return	mixed
	 */
	public function rightJoin(
		$join,
		$joinColumn,
		$operator = null,
		$tableColumn = null
	) {
		return $this->join(
			$join,
			$joinColumn,
			$operator,
			$tableColumn,
			'RIGHT'
		);
	}

	/**
	 * Adds a join between tables of type "Full Outer"
	 *
	 * @access	public
	 * @param	string				$join			Foreign table
	 * @param	string|Clousure		$joinColumn		Foreign table key|Closure for nested joins
	 * @param	string				$operator		Operator
	 * @param	string				$tableColumn	This table key
	 * @return	mixed
	 */
	public function fullOuterJoin(
		$join,
		$joinColumn,
		$operator = null,
		$tableColumn = null
	) {
		return $this->join(
			$join,
			$joinColumn,
			$operator,
			$tableColumn,
			'FULL OUTER'
		);
	}

	/**
	 * Returns a join condition
	 *
	 * @access	public
	 * @param	string		$join			Foreign table
	 * @param	string		$joinColumn		Foreign table key
	 * @param	string		$operator		Operator
	 * @param	string		$tableColumn	This table key
	 * @param	string		$type			On union type (and, or)
	 * @return	string
	 */
	public function addOn(
		$join,
		$joinColumn,
		$operator,
		$tableColumn,
		$type = 'AND'
	) {
		$joinColumn = is_array($joinColumn)
			? $joinColumn[0]
			: $this->prepareColumn($joinColumn, $join);
		$tableColumn = is_array($tableColumn)
			? $tableColumn[0]
			: $this->prepareColumn($tableColumn);
		return empty($this->on)
			? "{$joinColumn} {$operator} {$tableColumn}"
			: "{$type} {$joinColumn} {$operator} {$tableColumn}";
	}

	/**
	 * Adds a join condition
	 *
	 * @access	public
	 * @param	string		$joinColumn		Foreign table key
	 * @param	string		$operator		Operator
	 * @param	string		$tableColumn	This table key
	 * @param	string		$type			On union type (and, or)
	 * @return	mixed
	 */
	public function on($joinColumn, $operator, $tableColumn, $type = 'AND')
	{
		$this->on[] = $this->addOn(
			null,
			$joinColumn,
			$operator,
			$tableColumn,
			$type
		);
		return $this;
	}

	/**
	 * Adds a join condition of type "Or"
	 *
	 * @access	public
	 * @param	string		$joinColumn		Foreign table key
	 * @param	string		$operator		Operator
	 * @param	string		$tableColumn	This table key
	 * @return	mixed
	 */
	public function orOn($joinColumn, $operator, $tableColumn)
	{
		return $this->on($joinColumn, $operator, $tableColumn, 'OR');
	}

	/**
	 * Adds a join "on in" condition
	 *
	 * @access	public
	 * @param	string		$joinColumn		Foreign table key
	 * @param	array		$values			Values
	 * @param	string		$type			On union type (and, or)
	 * @param	string		$operator		Operator
	 * @return	mixed
	 */
	public function onIn(
		$joinColumn,
		array $values,
		$type = 'AND',
		$operator = 'IN'
	) {
		if (empty($values)) {
			return $this;
		}
		$data = $this->quote($values);
		return $this->on(
			$joinColumn,
			$operator,
			['(' . join(',', $data) . ')'],
			$type
		);
	}

	/**
	 * Adds a join "on not in" condition
	 *
	 * @access	public
	 * @param	string		$joinColumn		Foreign table key
	 * @param	array		$values			Values
	 * @param	string		$type			On union type (and, or)
	 * @return	mixed
	 */
	public function onNotIn($joinColumn, array $values, $type = 'AND')
	{
		return $this->onIn($joinColumn, $values, $type, 'NOT IN');
	}

	/**
	 * Builds the on conditions of a join closure
	 *
	 * @access	protected
	 * @param	SqlSentence		$sentence	Sentence
	 * @return	string
	 */
	protected function buildOn(SqlSentence $sentence)
	{
		return join(' ', $sentence->on);
	}

	/**
	 * Adds a having with "And" type
	 *
	 * Add a closure as first param for nested having
	 *
	 * @access	public
	 * @param	string/Closure	$compare	Column of value to compare|Closure for nested having
	 * @param	mixed			$operator	Operator
	 * @param	mixed			$to			Value of comparison
	 * @param	string			$type		Having union type (And, Or)
	 * @return	mixed
	 */
	public function having(
		$compare,
		$operator = null,
		$to = null,
		$type = 'AND'
	) {
		if ($compare instanceof Closure) {
			$sentence = $this->newSelf();
			$compare($sentence);
			$having = empty($this->having)
				? "({$this->buildHaving($sentence)})"
				: "{$type} ({$this->buildHaving($sentence)})";
			$this->having[] = $having;
			return $this;
		}
		if (!isset($to)) {
			$to = $operator;
			$operator = '=';
		}
		$to = is_array($to) ? $to[0] : $this->quote($to);
		$compare = is_array($compare)
			? $compare[0]
			: $this->prepareColumn($compare);
		$having = empty($this->having)
			? "{$compare} {$operator} {$to}"
			: "{$type} {$compare} {$operator} {$to}";
		$this->having[] = $having;
		return $this;
	}

	/**
	 * Builds the having conditions on the given sentence
	 *
	 * @access	protected
	 * @param	SqlSentence		$sentence	Sentence
	 * @return	string
	 */
	protected function buildHaving(SqlSentence $sentence)
	{
		return join(' ', $sentence->having);
	}

	/**
	 * Adds a having with "And" type
	 *
	 * Add a closure as first param for nested having
	 *
	 * @access	public
	 * @param	string/Closure	$compare	Column of value to compare|Closure for nested having
	 * @param	mixed			$operator	Operator
	 * @param	mixed			$to			Value of comparison
	 * @return	mixed
	 */
	public function andHaving($compare, $operator = null, $to = null)
	{
		return $this->having($compare, $operator, $to);
	}

	/**
	 * Adds a having with "Or" type
	 *
	 * Add a closure as first param for nested having
	 *
	 * @access	public
	 * @param	string/Closure	$compare	Column of value to compare|Closure for nested having
	 * @param	mixed			$operator	Operator
	 * @param	mixed			$to			Value of comparison
	 * @return	mixed
	 */
	public function orHaving($compare, $operator = null, $to = null)
	{
		return $this->having($compare, $operator, $to, 'OR');
	}

	/**
	 * Sets "group by" conditions
	 *
	 * @access	public
	 * @param	array		$group		Group by conditions
	 * @return	mixed
	 */
	public function groupBy(array $group)
	{
		$this->groupBy = $group;
		return $this;
	}

	/**
	 * Sets "order by" conditions
	 *
	 * @access	public
	 * @param	array		$order		Order by conditions
	 * @return	mixed
	 */
	public function orderBy(array $order)
	{
		$this->orderBy = $order;
		return $this;
	}

	/**
	 * Sets query "limit"
	 *
	 * @access	public
	 * @param	string		$limit		Query limit
	 * @return	mixed
	 */
	public function limit($limit)
	{
		$this->limit = $limit;
		return $this;
	}

	/**
	 * Sets query "index"
	 *
	 * @access	public
	 * @param	string		$index		Query index
	 * @return	mixed
	 */
	public function index($index)
	{
		$this->index = $index;
		return $this;
	}

	/**
	 * Builds a "select" sentence
	 *
	 * @access	public
	 * @return	string
	 */
	public function buildSelect()
	{
		$columns = join(',', $this->select);
		$query = "SELECT {$columns} FROM {$this->table}";
		if ($this->index !== '') {
			$query .= " USE INDEX ({$this->index})";
		}
		if (!empty($this->join)) {
			$query .= ' ' . join(' ', $this->join);
		}
		if (!empty($this->where)) {
			$query .= " WHERE {$this->buildWhere($this)}";
		}
		if (!empty($this->groupBy)) {
			$query .= ' GROUP BY ' . join(', ', $this->groupBy);
		}
		if (!empty($this->having)) {
			$query .= " HAVING {$this->buildHaving($this)}";
		}
		if (!empty($this->orderBy)) {
			$query .= ' ORDER BY ' . join(', ', $this->orderBy);
		}
		if ($this->limit !== '') {
			$query .= " LIMIT {$this->limit}";
		}
		return $query;
	}

	/**
	 * Builds an "insert" sentence
	 *
	 * @access	public
	 * @param	array		$insert		Table columns and values
	 * @return	string
	 */
	public function buildInsert(array $insert)
	{
		$fields = join(', ', $insert);
		$value = join(', ', array_fill(0, count($insert), '?'));
		return "INSERT INTO {$this->table} ({$fields}) VALUES ({$value})";
	}

	/**
	 * Buils an "update" sentence
	 *
	 * @access	public
	 * @param	array		$update		Table columns and values
	 * @return	string
	 */
	public function buildUpdate(array $update)
	{
		foreach ($update as &$field) {
			$field = "{$this->table}.{$field}=?";
		}
		$fields = join(',', $update);
		$joins = empty($this->join) ? '' : ' ' . join(' ', $this->join);
		return "UPDATE {$this->table}{$joins} SET {$fields} WHERE {$this->buildWhere(
			$this
		)}";
	}

	/**
	 * Builds a "delete" sentence
	 *
	 * @access	public
	 * @param	string		$table		When join sentence, set the table to delete from
	 * @return	string
	 */
	public function buildDelete($table = ' ')
	{
		$joins = empty($this->join) ? '' : ' ' . join(' ', $this->join);
		return "DELETE{$table}FROM {$this->table}{$joins} WHERE {$this->buildWhere(
			$this
		)}";
	}

	/**
	 * Appends table to column if needed
	 *
	 * @access	protected
	 * @param	string		$key		Table column
	 * @param	string		$table		Table name or alias
	 * @return	string
	 */
	protected function prepareColumn($key, $table = null)
	{
		if (!isset($table)) {
			$table = $this->table;
		}
		return strpos($key, '.') === false && strpos($key, '(') === false
			? "{$table}.{$key}"
			: $key;
	}

	/**
	 * Cleans query values
	 *
	 * @access	protected
	 * @param	mixed		$value		Value(s) to clean
	 * @return	mixed
	 */
	protected function quote($value)
	{
		return is_array($value)
			? array_map([$this, 'clean'], $value)
			: $this->clean($value);
	}

	/**
	 * Cleans query values
	 *
	 * @access	protected
	 * @param	mixed	$value		Value to clean
	 * @return	mixed
	 */
	protected function clean($value)
	{
		return !is_numeric($value) && $value !== '?'
			? addslashes($value)
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
		$self = new self();
		$self->table = $this->table;
		return $self;
	}

	/**
	 * Returns the string with a case when format
	 *
	 * @access	public
	 * @param	string	$field		Select field to be compared
	 * @param	array	$cases		Keys are cases, values are selected text
	 * @param	string	$as			Select alias
	 * @param	string	$else		Else de los cases
	 * @return	string
	 */
	public function cases($field, array $cases, $as, $else = null)
	{
		$builder = [];
		foreach ($cases as $key => $value) {
			$builder[] =
				'WHEN ' . $this->clean($key) . ' THEN ' . $this->clean($value);
		}
		if (isset($else)) {
			$builder[] = 'ELSE ' . $this->clean($else);
		}
		return 'CASE ' . $field . ' ' . join(' ', $builder) . ' END AS ' . $as;
	}
}
