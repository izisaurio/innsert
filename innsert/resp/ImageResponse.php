<?php

namespace innsert\resp;

use innsert\lang\Lang;

/**
 * Innsert PHP MVC Framework
 *
 * Image response
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class ImageResponse extends Response
{
	/**
	 * Image mime types
	 *
	 * @static
	 * @access	public
	 * @var		string
	 */
	const JPG = 'image/jpeg';
	const PNG = 'image/png';
	const GIF = 'image/gif';

	/**
	 * Image file path
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $file;

	/**
	 * Constructor
	 *
	 * Sets image, mime and prepares headers
	 *
	 * @access	public
	 * @param	string	$file	File path
	 * @param	string	$mime	Mime path
	 */
	public function __construct($file, $mime)
	{
		if (!file_exists($file)) {
			return (new RequestError(Lang::defaultInstance()->get('fileNotFound')))->send();
		}
		$this->header('Content-Type', $mime);
		$this->header('Content-Length', filesize($file));
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
		exit;
	}
}