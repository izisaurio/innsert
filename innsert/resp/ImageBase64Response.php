<?php

namespace innsert\resp;

/**
 * Innsert PHP MVC Framework
 *
 * Base64 encoded image response
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class ImageBase64Response extends Response
{
	/**
	 * Image types
	 *
	 * @static
	 * @access	public
	 * @var		string
	 */
	const JPG = 'image/jpeg';
	const PNG = 'image/png';
	const GIF = 'image/gif';

	/**
	 * Constructor
	 *
	 * Sets image data and prepares headers
	 *
	 * @access	public
	 * @param	string	$data	Image data
	 * @param	string	$mime	Mime type
	 */
	public function __construct($data, $mime)
	{
		parent::__construct();
		$this->header('Content-Type', $mime);
		$this->body = base64_decode($data);
	}
}
