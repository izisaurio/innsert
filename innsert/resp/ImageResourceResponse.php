<?php

namespace innsert\resp;

use innsert\views\View;

/**
 * Innsert PHP MVC Framework
 *
 * Image resource response
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class ImageResourceResponse extends Response
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
	 * Image resource
	 *
	 * @access	public
	 * @var		mixed
	 */
	public $image;

	/**
	 * Mime type
	 *
	 * @access	public
	 * @var		string
	 */
	public $mime;

	/**
	 * Constructor
	 *
	 * Sets image, mime and prepared headers
	 *
	 * @access	public
	 * @param	string	$image	Image resource
	 * @param	string	$mime	Mime type
	 */
	public function __construct($image, $mime)
	{
		parent::__construct();
		$this->header('Content-Type', $mime);
		$this->image = $image;
		$this->mime = $mime;
	}

	/**
	 * Sends response
	 * @access	public
	 */
	public function send()
	{
		$this->writeHeaders();
		switch ($this->mime) {
			case ImageResponse::JPG:
				imagejpeg($this->image);
				break;
			case ImageResponse::PNG:
				imagepng($this->image);
				break;
			case ImageResponse::GIF:
				imagegif($this->image);
				break;
		}
		imagedestroy($this->image);
		exit;
	}
}