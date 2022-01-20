<?php

namespace innsert\resp;

use innsert\lang\Lang;

/**
 * Innsert PHP MVC Framework
 *
 * Download file response
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Download extends Response
{
	/**
	 * File path
	 *
	 * @access	public
	 * @var		string
	 */
	public $file;

	/**
	 * Constructor
	 *
	 * Sets file and name, prepares headers
	 *
	 * @param	string	$file	File path
	 * @param	string	$name	Name of downloaded file
	 * @access	public
	 * @return	mixed
	 */
	public function __construct($file, $name = null)
	{
		if (!file_exists($file)) {
			return (new RequestError(
				Lang::defaultInstance()->get('fileNotFound')
			))->send();
		}
		$fileName = isset($name) ? $name : basename($file);
		$this->header('Content-Type', 'application/octet-stream')
			->header('Content-Description', 'File Transfer')
			->header('Content-Disposition', 'attachmen', [
				'filename' => $fileName,
			])
			->header('Content-Transfer-Encoding', 'binary')
			->header('Expires', '0')
			->header('Cache-Control', 'must-revalidate')
			->header('Pragma', 'public')
			->header('Content-Length', filesize($file));
		$this->file = $file;
	}

	/**
	 * Sends response
	 *
	 * @access	public
	 */
	public function send()
	{
		$this->writeHeaders();
		readfile($this->file);
		exit();
	}
}
