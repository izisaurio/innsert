<?php

namespace innsert\resp;

/**
 * Innsert PHP MVC Framework
 *
 * Creates a url redirect
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class Redirect
{
	/**
	 * Constructor
	 *
	 * Sets url and executes redirection
	 *
	 * @param	string	$url	Redirection url
	 * @access	public
	 */
	public function __construct($url)
	{
		header('Location: ' . $url, true, 302);
		exit();
	}
}
