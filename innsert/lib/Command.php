<?php

namespace innsert\lib;

/**
 * Innsert PHP MVC Framework
 *
 * Clase para ejecutar comandos mediante shell_exec
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class Command
{
	/**
	 * Command to execute
	 *
	 * @access	public
	 * @var		string
	 */
	public $command;

	/**
	 * PHP command template to run in background
	 *
	 * @access	public
	 * @var		string
	 */
	public $backgroundCommand = 'php -q [script] < /dev/null';

	/**
	 * PHP command template to run requential
	 *
	 * @access	public
	 * @var		string
	 */

	public $sequentialCommand = 'php -q [script]';

	/**
	 * Flag to prepend document root and app folder
	 *
	 * @access	public
	 * @var		bool
	 */
	public $prepend = true;

	/**
	 * Constructor
	 *
	 * Sets the commenda value
	 *
	 * @access	public
	 * @param	string	$command	Command to exec
	 */
	public function __construct($command)
	{
		$this->command = $command;
	}

	/**
	 * Runs a raw command
	 *
	 * @access	public
	 * @return	string
	 */
	public function run()
	{
		return shell_exec($this->command);
	}

	/**
	 * Runs a background PHP script
	 *
	 * @access	public
	 */
	public function runInBackground()
	{
		$command = $this->prepend ? Request::defaultInstance()->server('DOCUMENT_ROOT') . DS . 'app' . DS : $this->command;
		shell_exec(str_replace('[script]', $command, $this->backgroundCommand));
	}

	/**
	 * Runs a sequential PHP script
	 *
	 * @access	public
	 */
	public function runSequential()
	{
		$command = $this->prepend ? Request::defaultInstance()->server('DOCUMENT_ROOT') . DS . 'app' . DS : $this->command;
		shell_exec(str_replace('[script]', $command, $this->sequentialCommand));
	}
}