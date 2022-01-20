<?php

namespace innsert\jwt;

/**
 * Innsert PHP MVC Framework
 *
 * Class to build, parse and validate jwt tokens
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class Jwt
{
	/**
	 * Algorith hash of tokens, extend class to change, [0] real name, [1] for header
	 *
	 * @static
	 * @access	public
	 * @var		array
	 */
	public static $hash = ['sha256', 'HS256'];

	/**
	 * Token builder
	 *
	 * @static
	 * @param	string	$secret		Hash secret
	 * @param	array	$payload	Payload content (with no configs)
	 * @param	array	$configs	Token configs (appended to payload)
	 * @param	array	$header		Token header
	 * @access	public
	 * @return	Builder
	 */
	public static function build(
		$secret,
		array $payload,
		array $configs = [],
		array $header = []
	) {
		return new Builder($secret, self::$hash, $payload, $header, $configs);
	}

	/**
	 * Token parser
	 *
	 * @static
	 * @param	string	$secret		Hash secret
	 * @param	string	$jwt		Content to parse
	 * @access	public
	 * @return	Parser
	 */
	public static function parse($secret, $jwt)
	{
		return new Parser($secret, self::$hash, $jwt);
	}
}
