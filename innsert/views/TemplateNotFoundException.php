<?php

namespace innsert\views;

use \Exception;

/**
 * Innsert PHP MVC Framework
 *
 * View template missing exception
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class TemplateNotFoundException extends Exception
{
	/**
	 * Constructor
	 *
	 * @param	string	$file	Template path
	 * @access	public
	 */
	public function __construct($file)
	{
		parent::__construct("View file not found ({$file})");
	}
}