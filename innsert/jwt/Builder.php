<?php

namespace innsert\jwt;

use innsert\lib\Base64URL;

/**
 * Innsert PHP MVC Framework
 *
 * Jwt token builder
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Builder
{
	/**
	 * Token header
	 *
	 * @access	private
	 * @var		array
	 */
	private $header;

	/**
	 * Token payload (main content)
	 *
	 * @access	private
	 * @var		array
	 */
	private $payload;

	/**
	 * Token signature
	 *
	 * @access	private
	 * @var		string
	 */
	private $signature;

	/**
	 * Default hashing algorithm
	 *
	 * @access	protected
	 * @var		array
	 */
	private $hash = ['sha256', 'HS256'];

	/**
	 * Token secret for hashing
	 *
	 * @access	private
	 * @var		string
	 */
	private $secret;

	/**
	 * Default token configuration, is appended to payload
	 *
	 * Issuer -> Token issuer name
	 * NotBefore -> Time (in seconds) from wich from the token is valid
	 * Expires -> Time (in seconds) for the token to expire, 0 to no expiration
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $configs = [
		'jsonTokenId' => 'n/a',
		'issuer' => 'innsert',
		'notBeforeInSeconds' => 1,
		'expiresInSeconds' => 0,
	];

	/**
	 * Generated token
	 *
	 * @access	private
	 * @var		string
	 */
	private $token;

	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	string	$secret		Hash secret
	 * @param	array	$hash		Hasing algorithm
	 * @param	array	$payload	Payload content (with no configs)
	 * @param	array	$configs	Token configs (appended to payload)
	 * @param	array	$header		Token header
	 */
	public function __construct(
		$secret,
		array $hash,
		array $payload,
		array $configs = [],
		array $header = []
	) {
		$this->secret = $secret;
		$this->hash = $hash;
		$this->header = array_merge($header, [
			'typ' => 'JWT',
			'alg' => $this->hash[1],
		]);
		$this->configs = array_merge($this->configs, $configs);
		$time = time();
		$content = [
			'jti' => $this->configs['jsonTokenId'],
			'iss' => $this->configs['issuer'],
			'iat' => $time,
			'nbf' => $time + $this->configs['notBeforeInSeconds'],
			'data' => $payload,
		];
		if ($this->configs['expiresInSeconds'] > 0) {
			$content['exp'] = $time + $this->configs['expiresInSeconds'];
		}
		$this->payload = $content;
		$this->token = $this->build();
	}

	/**
	 * Build token
	 *
	 * @access	private
	 * @return	string
	 */
	private function build()
	{
		$header = Base64URL::encode(json_encode($this->header));
		$payload = Base64URL::encode(json_encode($this->payload));
		$signature = $this->signature = $this->buildSignature(
			$header,
			$payload
		);
		return "{$header}.{$payload}.{$signature}";
	}

	/**
	 * Build signature and tranform to Base64URL
	 *
	 * @access	private
	 * @param	string	$base64URLHeader	Base64URL transformed header
	 * @param	string	$base64URLPayload	Base64URL transformed payload
	 * @return	string
	 */
	private function buildSignature($base64URLHeader, $base64URLPayload)
	{
		$signature = hash_hmac(
			$this->hash[0],
			"{$base64URLHeader}.{$base64URLPayload}",
			$this->secret,
			true
		);
		return Base64URL::encode($signature);
	}

	/**
	 * Returns payload main data (no configs)
	 *
	 * @access	public
	 * @return	array
	 */
	public function getPayloadData()
	{
		return $this->payload['data'];
	}

	/**
	 * Returns build token
	 *
	 * @access	public
	 * @return	string
	 */
	public function getToken()
	{
		return $this->token;
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
