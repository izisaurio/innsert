<?php

namespace innsert\jwt;

use innsert\lib\Base64URL,
	\stdClass;

/**
 * Innsert PHP MVC Framework
 *
 * Parses a builded jwt string
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Parser
{
	/**
	 * Token header
	 *
	 * @access	public
	 * @var		stdClass	
	 */
	public $header;

	/**
	 * Token payload (main content)
	 *
	 * @access	public
	 * @var		stdClass
	 */
	public $payload;

	/**
	 * Token signature
	 *
	 * @access	public
	 * @var		string
	 */
	public $signature;

	/**
	 * Default hashing algorithm
	 *
	 * @access	public
	 * @var		array
	 */
	public $hash = ['sha256', 'HS256'];

	/**
	 * Token secret for hashing
	 *
	 * @access	public
	 * @var		string
	 */
	public $secret;

	/**
	 * Token string
	 *
	 * @access	public
	 * @var		stdClass
	 */
	public $token;

	/**
	 * Constructor
	 *
	 * Gets and decodes the jwt
	 *
	 * @access	public
	 * @param	string	$secret		Hash secret
	 * @param	array	$hash		Hasing algorithm
	 * @param	string	$token		Token string
	 */
	public function __construct($secret, array $hash, $token)
	{
		$this->secret = $secret;
		list($bearer, $jwt) = explode(' ', $token);
		$this->hash = $hash;
		$this->token = new stdClass;
		$this->token->code = $jwt;
		list($header, $payload, $signature) = explode('.', $jwt);
		$this->token->header = $header;
		$this->token->payload = $payload;
		$this->token->signature = $signature;
		$this->decode();
	}

	/**
	 * Decode header and payload
	 *
	 * @access	private
	 */
	private function decode()
	{
		$this->header = json_decode(Base64URL::decode($this->token->header));
		$this->payload = json_decode(Base64URL::decode($this->token->payload));
		$this->signature = $this->token->signature;
	}

	/**
	 * Returns token validator
	 *
	 * @access	public
	 * @return	Validator
	 */
	public function validator()
	{
		return new Validator($this);
	}

	/**
	 * Returns payload main data (no configs)
	 *
	 * @access	public
	 * @return	array
	 */
	public function getPayloadData()
	{
		return $this->payload->data;
	}

	/**
	 * ToString
	 *
	 * @access	public
	 * @return	string
	 */
	public function __toString()
	{
		return $this->token;
	}
}