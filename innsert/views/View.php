<?php

namespace innsert\views;

use innsert\core\Loader;

/**
 * Innsert PHP MVC Framework
 *
 * View base class
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
Loader::file([__DIR__, 'templateFunctions']);
class View
{
	/**
	 * Template path as string
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $fullPath;

	/**
	 * Template path
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $path;

	/**
	 * Master layout view
	 *
	 * @access	public
	 * @var		Master
	 */
	public $master;

	/**
	 * View processed content
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $content;

	/**
	 * Items gotten from controller
	 *
	 * @access	public
	 * @var		array
	 */
	public $items;

	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array	$path	Template path
	 * @param	array	$items	Items from controller
	 * @throws	TemplateNotFoundException
	 */
	public function __construct(array $path, array $items = [])
	{
		$this->fullPath =
			join(DS, array_merge(['public', 'views'], $path)) . EXT;
		if (!file_exists($this->fullPath)) {
			throw new TemplateNotFoundException($this->fullPath);
		}
		$this->path = $path;
		$this->items = $items;
	}

	/**
	 * Adds item to view
	 *
	 * @access	public
	 * @param	string|array	$key	Key for single element|Array of items
	 * @param	mixed			$value	Value of key for single items
	 * @return	View
	 */
	public function addItem($key, $value = null)
	{
		if (is_array($key)) {
			$this->items = array_merge($this->items, $key);
		} else {
			$this->items[$key] = $value;
		}
		return $this;
	}

	/**
	 * Parses template and saves it to content
	 *
	 * @access	public
	 */
	public function render()
	{
		ob_start();
		extract($this->items);
		require $this->fullPath;
		$this->content = ob_get_clean();
	}

	/**
	 * Imports a template to this one
	 *
	 * @access	public
	 * @param	array	$path	Template path
	 * @param	array	$items	Items to send to template (overrided current items)
	 * @return	View
	 */
	public function import(array $path, array $items = null)
	{
		$import = new self($path, isset($items) ? $items : $this->items);
		$import->render();
		return $import;
	}

	/**
	 * Add header to Master view
	 *
	 * @access	public
	 * @param	array	$headers	Header collection
	 * @throws	MasterViewNotFoundException
	 */
	public function addHeaders(array $headers)
	{
		if (!isset($this->master)) {
			throw new MasterViewNotFoundException($this->fullPath);
		}
		$this->master->addHeaders($headers);
	}

	/**
	 * Writes a collection of headers
	 *
	 * @access	public
	 * @param	array	$headers	Header collection
	 * @return	string
	 */
	public function getHeaders(array $headers)
	{
		return (new Headers($headers))->parse()->get();
	}

	/**
	 * Returns rendered view content
	 *
	 * @access	public
	 * @return	string
	 * @throws	TemplateNotRenderedException
	 */
	public function draw()
	{
		if (!$this->content) {
			throw new TemplateNotRenderedException($this->fullPath);
		}
		return $this->content;
	}

	/**
	 * Returns rendered view content
	 *
	 * @access	public
	 * @return	string
	 * @throws	TemplateNotRenderedException
	 */
	public function __toString()
	{
		return $this->draw();
	}
}
