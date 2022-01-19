<?php

namespace innsert\mvc;

use innsert\lib\DatePlus,
	innsert\sess\Sess;

/**
 * Innsert PHP MVC Framework
 *
 * Models default value manager
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class DefaultValue
{
	/**
	 * Model to set value
	 *
	 * @access	public
	 * @var		Model
	 */
	public $model;

	/**
	 * Value type
	 *
	 * @access	public
	 * @var		string
	 */
	public $type;

	/**
	 * Value
	 *
	 * @access	public
	 * @var		mixed
	 */
	public $value;

	/**
	 * Default value shortcuts
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $shortcuts = [
		'[now]'		=>	['DATETIME', 'now'],
		'[today]'	=>	['DATE', 'today'],
		'[time]'	=>	['TIME', 'time'],
		'[user]'	=>	['SESSION', '__user__:id']
	];

	/**
	 * Sets default value params
	 *
	 * @access	public
	 * @param	Model	$model		Model to set value
	 * @param	string	$rule		Default value rule
	 * @throws	DefaultTypeNotFoundException
	 */
	public function __construct(Model $model, $rule)
	{
		if (!is_int($rule) && !is_string($rule)) {
			throw new RawValueMistypedException(gettype($this->value));
		}
		$this->model = $model;
		if (array_key_exists($rule, $this->shortcuts)) {
			list($type, $value) = $this->shortcuts[$rule];
		} else {
			list($type, $value) = (!is_string($rule) || strpos($rule, '|') === false)
				? ['RAW', $rule] : explode('|', $rule);
		}
		$this->type = $type;
		$this->value = $value;
	}

	/**
	 * Returns default value
	 *
	 * @access	public
	 * @return	mixed
	 * @throws	DefaultTypeNotFoundException
	 */
	public function get()
	{
		switch ($this->type) {
			case 'RAW':
				return $this->value;
			case 'MODEL':
				if (!isset($this->model->{$this->value})) {
					throw new DefaultValueNotFoundException('MODEL', $this->value);
				}
				return $this->model->{$this->value};
			case 'SESSION':
				$session = Sess::defaultInstance();
				if (strpos($this->value, ':') === false) {
					if (!isset($session[$this->value])) {
						throw new DefaultValueNotFoundException('SESSION', $this->value);
					}
					return $session[$this->value];
				}
				list($k, $v) = explode(':', $this->value);
				if (!isset($session[$k][$v])) {
					throw new DefaultValueNotFoundException('SESSION', $this->value);
				}
				return $session[$k][$v];
			case 'DATETIME':
				if ($this->value === 'now') {
					return (new DatePlus)->toDB();
				}
				return (new DatePlus)->setTimestamp(strtotime($this->value))->toDB();
			case 'DATE':
				if ($this->value === 'today') {
					return (new DatePlus)->format('Y-m-d');
				}
				return (new DatePlus)->setTimestamp(strtotime($this->value))->format('Y-m-d');
			case 'TIME':
				if ($this->value === 'time') {
					return (new DatePlus)->toDBTime();
				}
				return (new DatePlus)->setTimestamp(strtotime($this->value))->toDBTime();
			default:
				throw new DefaultTypeNotFoundException($this->type);
		}
	}
}