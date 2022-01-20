<?php

namespace innsert\resp;

use innsert\views\View;

/**
 * Innsert PHP MVC Framework
 *
 * View response
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class ViewResponse extends Response
{
	/**
	 * Default response charset
	 *
	 * @access	public
	 * @var		array
	 */
	public $charset = ['charaset' => 'utf-8'];

	/**
	 * Constructor
	 *
	 * Sets view and prepares headers
	 *
	 * @access	public
	 * @param	View	$view	Response view
	 */
	public function __construct(View $view)
	{
		parent::__construct();
		$this->header('Content-Type', 'text/html', $this->charset);
		$view->render();
		$this->body = $view->draw();
	}
}
