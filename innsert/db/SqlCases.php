<?php

namespace innsert\db;

/**
 * Innsert PHP MVC Framework
 *
 * Creates a case select element for Sql Sentences
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class SqlCases
{
	/**
	 * Column to search on sentence
	 *
	 * @access	public
	 * @var		string
	 */
	public $column;

	/**
	 * Alias of selected field on sentence
	 *
	 * @access	public
	 * @var		string
	 */
	public $as;

	/**
	 * Collection with cases to select
	 *
	 * @access	public
	 * @var		array
	 */
	public $cases;

	/**
	 * Optional else if no cases match
	 *
	 * @access	public
	 * @var		string
	 */
	public $else;

	/**
	 * Constructor
	 *
	 * Sets source and mapper
	 *
	 * @access	public
	 * @param	string	$column		Select column to be compared
	 * @param	string	$as			Select alias
	 * @param	array	$cases		Keys are cases, values are selected text
	 * @param	string	$else		Else de los cases
	 */
	public function __construct(array $cases, $else = null)
	{
		$this->column = $column;
		$this->as = $as;
		$this->cases = $cases;
		$this->else = $else;
	}

	/**
	 * Builds de cases syntax and returns it
	 *
	 * @access	public
	 * @param	SqlSentence		$sentence	The SqlSentence
	 * @return	string
	 */
	protected function build(SqlSentence $sentence)
	{
		$builder = [];
		foreach ($this->cases as $key => $value) {
			$builder[] = 'WHEN ' . $sentence->clean($key) . ' THEN ' . $value;
		}
		if (isset($this->else)) {
			$builder[] = 'ELSE ' . $sentence->clean($this->else);
		}
		return 'CASE ' .
			$this->column .
			' ' .
			join(' ', $builder) .
			' END AS ' .
			$this->as;
	}
}
