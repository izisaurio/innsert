<?php

use innsert\lib\Url;

/**
 * Innsert PHP MVC Framework
 *
 * Global functions
 *
 * @author	izisaurio
 * @package	innsert/framework
 * @version	1
 */

/**
 * Returns a new Url instance
 *
 * @access	public
 * @param	mixed	$url	Url string, as array or multiple params
 * @return	Url
 */
function url($parts = array())
{
	return is_array($parts) ? new Url($parts) : new Url(func_get_args());
}