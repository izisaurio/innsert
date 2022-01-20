<?php

namespace innsert\lib;

/**
 * Innsert PHP MVC Framework
 *
 * Encodes and decodes text to Base64 url safe
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class Base64URL
{
	/**
	 * Encodes a sring to Base64URL
	 *
	 * @static
	 * @access	public
	 * @param	string	$text	Text to encode
	 * @return	string
	 */
	public static function encode($text)
	{
		return str_replace(
			['+', '/', '='],
			['-', '_', ''],
			base64_encode($text)
		);
	}

	/**
	 * Base64URL to string
	 *
	 * @static
	 * @access	public
	 * @param	string	$base64URL	Base64Url to decode
	 * @return	string
	 */
	public static function decode($base64URL)
	{
		return base64_decode(str_replace(['-', '_'], ['+', '/'], $base64URL));
	}
}
