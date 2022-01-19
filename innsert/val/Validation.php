<?php

namespace innsert\val;

use innsert\core\ErrorManager,
	innsert\mvc\Model,
	innsert\lang\Lang;

/**
 * Innsert PHP MVC Framework
 *
 * Model validation manager
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Validation extends ErrorManager
{
	/**
	 * Error message file path
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $_path = ['app', 'configs', 'labels', 'validations'];

	/**
	 * Model to validate
	 *
	 * @access	public
	 * @var		Model
	 */
	public $model;

	/**
	 * Validation rules
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $rules = [];

	/**
	 * Classes with validations
	 * 
	 * @access	protected
	 * @var		array
	 */
	protected $plugins = [
		'innsert\\val\\Rules'
	];

	/**
	 * Keys to ignore in rules
	 * 
	 * @access	protected
	 * @var		array
	 */
	protected $ignore = [
		'OPTIONAL',
		'DEFAULT',
		'UNION',
		'CLASS',
		'ALIAS'
	];

	/**
	 * All validation methods
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $methods = [];

	/**
	 * Constructor
	 *
	 * Sets model and rules
	 *
	 * @access	public
	 * @param	Model	$model		Model to validate
	 * @param	array	$rules		With this rules
	 */
	public function __construct(Model $model, array $rules)
	{
		parent::__construct();
		$this->model = $model;
		$this->rules = $rules;
		foreach ($this->plugins as $plugin) {
			$methods = get_class_methods($plugin);
			foreach ($methods as $method) {
				$this->methods[$method] = $plugin;
			}
		}
	}

	/**
	 * Checks validations
	 * 
	 * @access	public
	 * @return	bool
	 */
	public function check()
	{
		$model = $this->model->toArray();
		foreach ($this->rules as $key => $rules) {
			if (!is_array($rules)) {
				continue;
			}
			$value = isset($model[$key]) ? trim($model[$key]) : '';
			if ($value === '' && !array_key_exists('OPTIONAL', $rules) && !array_key_exists('DEFAULT', $rules)) {
				$this->addError('REQUIRED', [$this->ruleKeyAlias($key)]);
				continue;
			}
			foreach ($rules as $rule => $data) {
				if (!in_array($rule, $this->ignore)) {
					if (!array_key_exists($rule, $this->methods)) {
						throw new RuleDoesNotExistException($rule, join(', ', array_unique($this->methods)));
					}
					$class = $this->methods[$rule];
					$complement = $data;
					if (is_bool($data)) {
						$parameters = [$value];
					} elseif (is_string($data) && $data[0] === '@') {
						$property = ltrim($data, '@');
						$parameters = [$value, $model[$property]];
						$complement = $this->ruleKeyAlias($property);
					} else {
						$parameters = [$value, $data];
					}
					if (!call_user_func_array([$class, $rule], $parameters)) {
						$this->addError($rule, [$this->ruleKeyAlias($key), $complement]);
					}
				}
			}
		}
		return empty($this->errors);
	}

	/**
	 * Gets the key alias, if none returns key name
	 * 
	 * @access	protected
	 * @param	string	$key	Model key to get alias from
	 * @return	string
	 */
	protected function ruleKeyAlias($key)
	{
		if (!isset($this->rules[$key])) {
			return ucfirst($key);
		}
		if (!isset($this->rules[$key]['ALIAS'])) {
			return ucfirst($key);
		}
		return !is_array($this->rules[$key]['ALIAS']) ? $this->rules[$key]['ALIAS'] :
			$this->rules[$key]['ALIAS'][Lang::defaultInstance()->locale];
	}
}