<?php

namespace innsert\files;

use innsert\core\ErrorManager,
	\Closure;

/**
 * Innsert PHP MVC Framework
 *
 * Helper class for file upload
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Uploader extends ErrorManager
{
	/**
	 * Error messages file path
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $_path = ['app', 'configs', 'labels', 'uploader'];

	/**
	 * Files to upload collection
	 *
	 * @access	public
	 * @var		array
	 */
	public $files = [];

	/**
	 * Upload path
	 *
	 * @access	public
	 * @var		string
	 */
	public $uploadPath;

	/**
	 * Allowed mime types
	 *
	 * @access	public
	 * @var		array
	 */
	public $allowedMimes;

	/**
	 * Flag for replacing a file if already exists
	 *
	 * @access	public
	 * @var		bool
	 */
	public $replace;

	/**
	 * Constructor
	 *
	 * Sets files and configs
	 *
	 * @access	public
	 * @param	array		$files		Files to upload
	 * @param	array		$path		Upload path
	 * @param	array		$mimes		Allowed mime types
	 * @param	bool		$replace	Flag for replacing a file if already exists
	 * @throws	ConfigFileNotFoundException
	 */
	public function __construct(array $files, array $path = array(), array $mimes = null, $replace = false)
	{
		parent::__construct();
		$this->uploadPath = empty($path) ? '' : (new Folder($path))->realPath;
		$items = [];
		if (!empty($files) && isset($files['name'])) {
			if (is_array($files['name'])) {
				foreach (array_keys($files['name']) as $key) {
					if (empty($files['name'][$key])) {
						continue;
					}
					$items[] = [
						'name'		=> $files['name'][$key],
						'type'		=> $files['type'][$key],
						'tmp_name'	=> $files['tmp_name'][$key],
						'error'		=> $files['error'][$key],
						'size'		=> $files['size'][$key],
					];
				}
			} else {
				if (!empty($files['name'])) {
					$items[] = $files;
				}
			}
			foreach ($items as $item) {
				$this->files[] = new UploadFile($item, $this);
			}
		}
		$this->allowedMimes = $mimes;
		$this->replace = $replace;
	}

	/**
	 * Sets allowed mime types
	 *
	 * @access	public
	 * @param	array	$allowedMimes	Allowed mime types
	 * @return	Uploader
	 */
	public function setAllowedMimes(array $allowedMimes)
	{
		$this->allowedMimes = $allowedMimes;
		return $this;
	}

	/**
	 * Sets flag for replacing existing files
	 *
	 * @access	public
	 * @param	bool	$value		Flag value
	 * @return	Uploader
	 */
	public function isReplaceable($value)
	{
		$this->replace = $value;
		return $this;
	}

	/**
	 * Executes file validations
	 *
	 * @access	public
	 * @return	Uploader
	 * @throws	ControllerControlledException
	 */
	public function check()
	{
		foreach ($this->files as $file) {
			$file->check();
		}
		return $this;
	}

	/**
	 * Saves file
	 *
	 * @access	public
	 * @throws	ControllerControlledException
	 */
	public function save()
	{
		foreach ($this->files as $file) {
			$file->save();
		}
		return $this;
	}

	/**
	 * Deletes uploaded files
	 *
	 * @access	public
	 * @return	Uploader
	 */
	public function delete()
	{
		foreach ($this->files as $file) {
			$file->delete();
		}
		return $this;
	}

	/**
	 * Sets closure for the first file in collection
	 *
	 * @access	public
	 * @param	Closure	$closure	Closure to execute, receives the file and this
	 * @return	Upload
	 */
	public function first(Closure $closure)
	{
		$closure((isset($this->files[0]) ? $this->files[0] : null), 0, $this);
		return $this;
	}

	/**
	 * Sets closure for each first file in collection
	 *
	 * @access	public
	 * @param	Closure	$closure	Closure to execute, receives the file and this
	 * @return	Uploader
	 */
	public function each(Closure $closure)
	{
		foreach ($this->files as $key => $file) {
			$closure($file, $key, $this);
		}
		return $this;
	}
}