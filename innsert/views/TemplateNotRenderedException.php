<?php

namespace innsert\views;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Thrown when trying to get content of a not yet proccessed template
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class TemplateNotRenderedException extends Exception
{
	/**
	 * Constructor
	 *
	 * @param	string	$file	Template path
	 * @access	public
	 */
	public function __construct($file)
	{
		parent::__construct("View not rendered ({$file})");
	}
}