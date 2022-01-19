<?php

namespace innsert\core;

use innsert\core\Config,
	innsert\lib\HttpRequest;

/**
 * Innsert PHP MVC Framework
 *
 * Framework router, needs a config file with route info
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Router extends Config
{
    /**
     * Ruta del archivo de configuración
     *
     * @access	protected
     * @var		array
     */
    protected $_path = ['app', 'configs', 'router'];

    /**
     * Default param regex names
     * 
     * @access  private
     * @var     array
     */
    private $defaultParams = ['id', 'name', 'page'];

    /**
     * Default path regex names
     * 
     * @access  private
     * @var     array
     */
    private $defaultPath = ['controller', 'action'];

    /**
     * Url de la petición actual
     *
     * @access	public
     * @var		string
     */
    public $url;

    /**
	 * Route received in url
	 *
	 * @access	public
	 * @var		string
	 */
	public $route;

    /**
     * Partes del controlador
     *
     * @access	public
     * @var		array
     */
    public $controller = [];

    /**
     * Acción a ejecutar en el controlador
     *
     * @access	public
     * @var		string
     */
    public $action;

    /**
     * Parametros de la petición
     *
     * @access	public
     * @var		array
     */
    public $params = [];

    /**
     * No match route flag
     * 
     * @access  public
     * @var     bool 
     */
    public $noRouteMatch = false;

    /**
     * Constructor
     *
     * Parsea la url recibida para generar las partes de la petición
     *
     * @access	public
     */
    public function __construct(HttpRequest $request)
    {
        parent::__construct();
        $path = REWRITES ? PATH : DS . SCRIPT . PATH;
        $this->url = $request->server('REQUEST_URI');
        $uri = substr(strtok($this->url, '?'), strlen($path));
        $this->route = "/$uri";
        $data = $this->parse();
        $this->process($data);
    }

    /**
     * Parses routes file and matches the controller to call
     *
     * @access	private
     * @return  array
     */
    private function parse()
    {
        $keys = array_keys($this->_items);
        if (in_array($this->route, $keys)) {
            $data = $this->_items[$this->route];
            $path = is_array($data) ? $data['path'] : $data;
            $params = is_array($data) && isset($data['params']) ? $data['params'] : [];
            return [
                'path'  => $path,
                'params'=> $params
            ];
        }
        $dynamics = array_filter($keys, [$this, 'filterDynamic']);
        $paramNames = isset($this->items['params']) ? $this->_items['_params'] : $this->defaultParams;
        $pathNames = isset($this->items['path']) ? $this->_items['_path']  : $this->defaultPath;
        $merged = array_merge($paramNames, $pathNames);
        $keywords = ['/' => '\\/'];
        foreach ($merged as $keyword) {
            $keywords[":{$keyword}"] = "(?<{$keyword}>[\w\-]+)";
        }
        foreach ($dynamics as $route) {
            $regex = str_replace(array_keys($keywords), $keywords, $route);
            if (preg_match("/^{$regex}$/", $this->route, $matches)) {
                $data = $this->_items[$route];
                $path = is_array($data) ? $data['path'] : $data;
                $params = is_array($data) && isset($data['params']) ? $data['params'] : [];
                foreach ($matches as $key => $value) {
                    if (!is_int($key)) {
                        if (in_array($key, $pathNames)) {
                            $path = str_replace(":${key}", $value, $path);
                        }
                        if (in_array($key, $paramNames)) {
                            $path = str_replace("/:${key}", '', $path);
                            $params[$key] = $value;
                        }
                    }
                }
                return [
                    'path'  => $path,
                    'params'=> $params
                ];
            }
        }
        return [];
    }

    /**
     * Process url data
     * 
     * @access  private
     * @param   array   $data   Controller data
     */
    private function process(array $data)
    {
        if (empty($data)) {
            $this->noRouteMatch = true;
            return;
        }
        $controller = explode('/', $data['path']);
        $action = array_pop($controller);
        $this->controller = array_map([$this, 'format'], $controller);
        $this->action = $this->format($action);
        $this->params = $data['params'];
    }

    /**
     * Filters array value is dynamic
     * 
     * @access  private
     * @param   mixed   $value  Array value to be filtered
     * @return  bool
     */
    private function filterDynamic($value) {
        return strpos($value, ':') !== false;
    }

    /**
     * Formats a path values to replace "-" with "_"
     *
     * @access	private
     * @param	string	$value	Path value
     * @return	string
     */
    private function format($value)
    {
        return str_replace('-', '_', $value);
    }
}
