<?php

namespace innsert\eng;

use innsert\db\DBMapper, innsert\views\LanguageView;

/**
 * Innsert PHP MVC Framework
 *
 * Pagination helper class
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Pagination implements PaginationInterface
{
	/**
	 * Configs
	 *
	 * @access	public
	 * @var		array
	 */
	public $configs;

	/**
	 * Counted results
	 *
	 * @access	public
	 * @var		int
	 */
	public $counted;

	/**
	 * Total pages result
	 *
	 * @access	public
	 * @var		int
	 */
	public $totalPages;

	/**
	 * Search result object
	 *
	 * @access	public
	 * @var		Result
	 */
	public $result;

	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array		$configs	Pagination configurations
	 */
	public function __construct(array $configs = [])
	{
		$defaults = [
			'showPerPage' => 50,
			'urlBase' => '/',
			'urlParams' => [],
			'index' => 1,
			'view' => ['_extra', 'defaultPagination'],
		];
		$this->configs = array_merge($defaults, $configs);
		foreach ($this->configs as $config => $value) {
			$this->$config = $value;
		}
	}

	/**
	 * Executes search and sets results and pagination
	 *
	 * @access	public
	 * @param	DBMapper	$mapper		DBMapper where the search will be made
	 */
	public function paginate(DBMapper $mapper)
	{
		$counter = clone $mapper;
		$counter->select = [];
		$this->counted = $counter->count();
		$this->totalPages = max(ceil($this->counted / $this->showPerPage), 1);
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

	/**
	 * Returns a view with the pagination to be included in views
	 *
	 * @access	public
	 * @return	string
	 */
	public function draw()
	{
		$view = new LanguageView($this->view, ['pagination' => $this]);
		$view->render();
		return $view->draw();
	}
}
