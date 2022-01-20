<?php

namespace innsert\eng;

use innsert\db\DBMapper;

/**
 * Innsert PHP MVC Framework
 *
 * Small pagination class, with "load more" use in mind
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class LitePagination implements PaginationInterface
{
	/**
	 * Configs
	 *
	 * @access	public
	 * @var		array
	 */
	public $configs;

	/**
	 * Search results
	 *
	 * @access	public
	 * @var		Result
	 */
	public $result;

	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array		$configs	Pagination configs
	 */
	public function __construct(array $configs = [])
	{
		$defaults = [
			'showPerPage' => 20,
			'index' => 1,
		];
		$this->configs = array_merge($defaults, $configs);
		foreach ($this->configs as $config => $value) {
			$this->$config = $value;
		}
	}

	/**
	 * Executes search and sets result
	 *
	 * @access	public
	 * @param	DBMapper	$mapper		DBMapper where the search will be made
	 */
	public function paginate(DBMapper $mapper)
	{
		$startPage = $this->index * $this->showPerPage - $this->showPerPage;
		$this->result = $mapper
			->limit("{$startPage}, {$this->showPerPage}")
			->find();
	}

	/**
	 * Returns the result of a serach
	 *
	 * @access	public
	 * @return	Result
	 */
	public function result()
	{
		return $this->result;
	}
}
