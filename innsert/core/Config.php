<?php

namespace innsert\core;

/**
 * Innsert PHP MVC Framework
 *
 * Base class for classes with a config file like format
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
abstract class Config extends ArrayLike
{
	/**
	 * Config file path
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $_path = [];

	/**
	 * Constructor
	 *
	 * Requires the config file and sets it as an array of this class
	 *
	 * @access	public
	 * @throws	JsonFileNotFoundException|JsonParseException
	 */
	public function __construct()
	{
		$this->_items = Loader::json($this->_path);
	}
}
