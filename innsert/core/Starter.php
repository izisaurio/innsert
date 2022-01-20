<?php

namespace innsert\core;

use innsert\lib\Request,
	innsert\resp\Response,
	innsert\resp\ErrorResponse,
	innsert\resp\EmptyResponse,
	\Exception;

/**
 * Innsert PHP MVC Framework
 *
 * Framework starter class
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Starter
{
	/**
	 * DefaultConfigs instance
	 *
	 * @access	public
	 * @var		DefaultConfig
	 */
	public $defaultConfigs;

	/**
	 * Request class instance
	 *
	 * @access	public
	 * @var		HttpRequest
	 */
	public $request;

	/**
	 * Router class instance
	 *
	 * @access	public
	 * @var		Router
	 */
	public $router;

	/**
	 * Response to send back
	 *
	 * @access	public
	 * @var		Response
	 */
	public $response;

	/**
	 * Constructor
	 *
	 * Sets values from config file
	 *
	 * @param	string	$file	Starter file
	 * @access	public
	 */
	public function __construct($file)
	{
		$this->request = Request::defaultInstance();
		define('US', '/');
		$this->setStarterFilePath($file);
		$this->defaultConfigs = Defaults::defaultInstance();
		define('DS', $this->defaultConfigs['ds']);
		$this->setEnvironmentValues();
		$this->router = new Router($this->request);
		$this->request->router = $this->router;
		Loader::file(['app', 'configs', 'functions']);
	}

	/**
	 * Defines the start file path
	 *
	 * @param	string	$file	Starter file
	 * @access	private
	 */
	private function setStarterFilePath($file)
	{
		$attrs = pathinfo($file);
		define('EXT', '.' . $attrs['extension']);
		define('SCRIPT', $attrs['basename']);
		$directory = dirname(
			substr($file, strlen($this->request->server('DOCUMENT_ROOT')))
		);
		$path =
			$directory === '/' ? US : str_replace('\\', US, $directory) . US;
		define('PATH', $path);
	}

	/**
	 * Defines main constants
	 *
	 * @access	private
	 */
	private function setEnvironmentValues()
	{
		define('DOMAIN', $this->defaultConfigs['domain']);
		define('REWRITES', $this->defaultConfigs['rewrites']);
		define('DEBUG', $this->defaultConfigs['debug']);
		error_reporting($this->defaultConfigs['reporting']);
		setlocale(LC_TIME, $this->defaultConfigs['locale']);
		date_default_timezone_set($this->defaultConfigs['timezone']);
	}

	/**
	 * Loads the called controller
	 *
	 * @access	public
	 * @return	Starter
	 */
	public function load()
	{
		if ($this->router->noRouteMatch) {
			$this->response = new ErrorResponse(
				"Route Not Found [{$this->router->route}]"
			);
			return $this;
		}
		$class =
			'app\controllers\\' .
			join('\\', $this->router->controller) .
			'Controller';
		if (!class_exists($class)) {
			$this->response = new ErrorResponse("Class [{$class}] not found");
			return $this;
		}
		$controller = new $class();
		if (method_exists($controller, '_middleware')) {
			$middlewareResponse = call_user_func([$controller, '_middleware']);
			if ($middlewareResponse instanceof Response) {
				$this->response = $middlewareResponse;
				return $this;
			}
		}
		$actionWithMethod = "{$this->request->method}_{$this->router->action}";
		if (method_exists($controller, $actionWithMethod)) {
			$this->response = call_user_func_array(
				[$controller, $actionWithMethod],
				$this->router->params
			);
		} elseif (method_exists($controller, $this->router->action)) {
			$this->response = call_user_func_array(
				[$controller, $this->router->action],
				$this->router->params
			);
		} else {
			$this->response = new ErrorResponse(
				"Action not found: [{$class}]->[{$this->router->action}]"
			);
		}
		return $this;
	}

	/**
	 * Sends response
	 *
	 * @access	public
	 */
	public function respond()
	{
		if (!($this->response instanceof Response)) {
			$this->response = new EmptyResponse('No response generated');
		}
		if (!empty($this->defaultConfigs['defaultHeaders'])) {
			$this->response->headers($this->defaultConfigs['defaultHeaders']);
		}
		$this->response->send();
	}
}
