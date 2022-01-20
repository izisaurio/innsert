<?php

namespace innsert\db;

use innsert\core\Forable, \StdClass;

/**
 * Innsert PHP MVC Framework
 *
 * Sentence param collection
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Params extends Forable
{
	/**
	 * Adds a param to collection
	 *
	 * @access	public
	 * @param	string	$attr		Param type
	 * @param	mixed	$value		Param value
	 */
	public function add($attr, $value)
	{
		$param = new StdClass();
		$param->attr = $attr;
		$param->value = $value;
		$this->_items[] = $param;
	}
}
