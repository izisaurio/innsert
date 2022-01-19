<?php

namespace innsert\resp;

use innsert\views\LanguageView;

/**
 * Innsert PHP MVC Framework
 *
 * Error response
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class ErrorResponse extends Response
{
	/**
	 * 404 view path
	 *
	 * @access	public
	 * @var		array
	 */
	public $path = ['_extra', 'PageNotFound'];
	
	/**
	* Response code
	*
	* @access	public
	* @var		int
	*/
	public $code = 404;

	/**
	 * Response charset
	 *
	 * @access	public
	 * @var		array
	 */
	public $charset = ['charaset' => 'utf-8'];

	/**
	 * Message to append to error
	 * 
	 * @access	protected
	 * @var		string
	 */
	protected $append = '[Controlled error response]';

	/**
	 * Constructor
	 *
	 * Creates the response
	 *
	 * @param	string	$error	Error message
	 * @access	public
	 */
	public function __construct($error)
	{
		parent::__construct();
		if (DEBUG) {
			$this->body = $error . '<br/>' . $this->append;
		} else {
			$this->header('Content-Type', 'text/html', $this->charset);
			$view = new LanguageView($this->path);
			$view->render();
			$this->body = $view->draw();
		}
	}
}