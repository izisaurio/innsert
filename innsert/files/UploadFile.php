<?php

namespace innsert\files;

use innsert\mvc\ControllerControlledException;

/**
 * Innsert PHP MVC Framework
 *
 * Represents a file parsed in Upload class
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class UploadFile
{
	/**
	 * File source name
	 *
	 * @access	public
	 * @var		string
	 */
	public $sourceName;

	/**
	 * FGile extension
	 *
	 * @access	public
	 * @var		string
	 */
	public $extension;

	/**
	 * File upload path
	 *
	 * @access	public
	 * @var		string
	 */
	public $path;

	/**
	 * Parent Uploader class
	 *
	 * @access	protected
	 * @var		Uploader
	 */
	protected $uploader;

	/**
	 * Constructor
	 *
	 * Sets the file data and parent Uploader
	 *
	 * @access	public
	 * @param	array	$data		File data
	 * @param	array	$uploader	Uploader
	 */
	public function __construct(array $data, Uploader $uploader)
	{
		foreach ($data as $key => $value) {
			$this->{$key} = $value;
		}
		$this->sourceName = $this->name;
		$this->extension = pathinfo($this->name, PATHINFO_EXTENSION);
		$this->uploader = $uploader;
		$this->updatePath();
	}

	/**
	 * Updates current file upload full path
	 *
	 * @access	protected
	 */
	protected function updatePath()
	{
		$this->path = $this->uploader->uploadPath . DS . $this->name;
	}

	/**
	 * Checks if file contains no errors
	 *
	 * @access	public
	 * @return	bool
	 */
	public function isOk()
	{
		return $this->error === UPLOAD_ERR_OK;
	}

	/**
	 * Checks if file is not empty
	 *
	 * @access	public
	 * @return	bool
	 */
	public function isUploaded()
	{
		return $this->error != UPLOAD_ERR_NO_FILE;
	}

	/**
	 * Renames file and updates real upload path
	 *
	 * @access	public
	 * @param	string		$name	New file name
	 * @return	UploadFile
	 */
	public function rename($name)
	{
		$this->name = strpos($name, '.') === false ? "{$name}.{$this->extension}" : $name;
		$this->updatePath();
		return $this;
	}

	/**
	 * Validates file
	 *
	 * @access	public
	 * @return	UploadFile
	 * @throws	ControllerControlledException
	 */
	public function check()
	{
		if (!$this->isOk()) {
			throw new ControllerControlledException($this->uploader->getError($this->error, $this->sourceName));
		}
		if (isset($this->uploader->allowedMimes)) {
			if (!in_array($this->type, $this->uploader->allowedMimes)) {
				throw new ControllerControlledException($this->uploader->getError('mimes', $this->sourceName));
			}
		}
		return $this;
	}

	/**
	 * Saves file to path
	 *
	 * @access	public
	 * @return	UploadFile
	 * @throws	ControllerControlledException
	 */
	public function save()
	{
		if (!$this->uploader->replace && $this->exists()) {
			throw new ControllerControlledException($this->uploader->getError('replace', $this->sourceName));
		}
		if (move_uploaded_file($this->tmp_name, $this->path) === false) {
			throw new ControllerControlledException($this->uploader->getError('save', $this->sourceName));
		}
		return $this;
	}

	/**
	 * Returns real full path
	 *
	 * @access	public
	 * @throws	PathNotSetException
	 * @return	string
	 */
	public function getFullPath()
	{
		if (!isset($this->path)) {
			throw new PathNotSetException($this->sourceName);
		}
		return $this->path;
	}

	/**
	 * Returns if file exists in same path
	 *
	 * @access	public
	 * @return	bool
	 */
	public function exists()
	{
		return file_exists($this->path);
	}

	/**
	 * Deletes the file
	 *
	 * @access	public
	 * @return	UploadFile
	 */
	public function delete()
	{
		if (isset($this->path) && file_exists($this->path)) {
			unlink($this->path);
		}
		return $this;
	}
}