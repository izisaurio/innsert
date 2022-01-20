<?php

namespace innsert\files;

/**
 * Innsert PHP MVC Framework
 *
 * Creates or finds a folder
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Folder
{
	/**
	 * Folder path
	 *
	 * @access	public
	 * @var		array
	 */
	public $path;

	/**
	 * Folder path in string
	 *
	 * @access	public
	 * @var		string
	 */
	public $realPath;

	/**
	 * Constructor
	 *
	 * Sets folder path, if make=true creates folder
	 *
	 * @access	public
	 * @param	array	$path	Folder path
	 * @param	bool	$make	Create folder flag
	 */
	public function __construct(array $path, $make = true)
	{
		$this->path = $path;
		$this->realPath = join(US, $this->path);
		if ($make) {
			$this->make();
		}
	}

	/**
	 * If folder doesn't exists makes it
	 *
	 * @access	protected
	 */
	protected function make()
	{
		$currentPath = '';
		foreach ($this->path as $folder) {
			$currentPath .= empty($currentPath) ? $folder : US . $folder;
			if (!is_dir($currentPath)) {
				mkdir($currentPath);
			}
		}
	}
}
