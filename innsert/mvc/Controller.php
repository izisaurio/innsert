<?php

namespace innsert\mvc;

use innsert\lib\Request,
	innsert\lib\HttpRequest,
	innsert\views\LanguageView,
	innsert\views\LanguageMaster,
	innsert\resp\ViewResponse,
	innsert\resp\JsonResponse,
	innsert\resp\Download,
	innsert\resp\ErrorResponse,
	innsert\resp\Redirect;

/**
 * Innsert PHP MVC Framework
 *
 * App controller class, handles request responses
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Controller
{
	/**
	 * Request instance
	 *
	 * @access	public
	 * @var		HttpRequest
	 */
	public $request;

	/**
	 * Elements to be sent to views
	 *
	 * @access	public
	 * @var		object|array
	 */
	public $items;

	/**
	 * Constructor
	 *
	 * Sets default items
	 *
	 * @access	public
	 */
	public function __construct()
	{
		$this->request = Request::defaultInstance();
	}

	/**
	 * Creates a view response using current router values
	 *
	 * @access	protected
	 * @param	array	$master		Master view path
	 * @param	array	$template	View template path if not using router values
	 * @return	ViewResponse
	 */
	protected function view(array $master = null, array $template = array())
	{
		$path = !empty($template) ? $template :
			array_merge($this->request->router->controller, ['action' => $this->request->router->action]);
		$view = new LanguageView($path, (array) $this->items);
		if (isset($master)) {
			$view->master = new LanguageMaster($master, array_merge((array) $this->items, ['view' => $view]));
			$view->render();
			return new ViewResponse($view->master);
		}
		return new ViewResponse($view);
	}

	/**
	 * Creates a json response
	 *
	 * @access	protected
	 * @param	array	$json		Array to be converted to json
	 * @param	array	$headers	Optional headers
	 * @return	JsonResponse
	 */
	protected function json(array $json, array $headers = array())
	{
		$response = new JsonResponse($json);
		if (empty($headers)) {
			return $response;
		}
		$response->headers($headers);
		return $response;
	}

	/**
	 * Creates a file download response
	 *
	 * @access	protected
	 * @param	string	$file	File path
	 * @param	string	$name	Name to downloaded file
	 * @return	Download
	 */
	protected function download($file, $name = null)
	{
		return new Download($file, $name);
	}

	/**
	 * Creates a 404 response
	 *
	 * @access	protected
	 * @param	string	$message	Message when in DEBUG mode
	 * @return	ErrorResponse
	 */
	protected function notFound($message = '404')
	{
		return new ErrorResponse($message);
	}

	/**
	 * Creates a redirect
	 *
	 * @access	protected
	 * @param	string	$url	Redirection url
	 * @return	Redirect
	 */
	protected function redirect($url)
	{
		return new Redirect($url);
	}
}