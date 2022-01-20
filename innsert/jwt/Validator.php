<?php

namespace innsert\jwt;

use innsert\lib\Base64URL;

/**
 * Innsert PHP MVC Framework
 *
 * Validates parsed jwt tokens
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Validator
{
	/**
	 * Parsed token
	 *
	 * @access	private
	 * @var		Parser
	 */
	private $jwt;

	/**
	 * Error message when validation fails
	 *
	 * @access	private
	 * @var		string
	 */
	private $message;

	/**
	 * Default error messages, extend to change
	 *
	 * @access	protected
	 * @var	array
	 */
	protected $messages = [
		'STRUCTURE' => 'Token does not have the correct structure',
		'SIGNATURE' => 'Token signature does not match',
		'NOTBEFORE' => 'Token is not expected yet',
		'EXPIRED' => 'Token expired',
	];

	/**
	 * Constructor
	 *
	 * Received parsed jwt
	 *
	 * @access	public
	 * @param	Parser	$jwt	Parsed token
	 */
	public function __construct(Parser $jwt)
	{
		$this->jwt = $jwt;
	}

	/**
	 * Validate structure
	 *
	 * @access	private
	 * @return	bool
	 */
	private function structure()
	{
		$ok =
			preg_match(
				'/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
				$this->jwt->token->code
			) === 1;
		if (!$ok) {
			$this->message = $this->messages['STRUCTURE'];
		}
		return $ok;
	}

	/**
	 * Validate signature
	 *
	 * @access	private
	 * @return	bool
	 */
	private function signature()
	{
		$token = $this->jwt->token;
		$obtainedSignature = hash_hmac(
			$this->jwt->hash[0],
			"{$token->header}.{$token->payload}",
			$this->jwt->secret,
			true
		);
		$ok = hash_equals(
			Base64URL::encode($obtainedSignature),
			$token->signature
		);
		if (!$ok) {
			$this->message = $this->messages['SIGNATURE'];
		}
		return $ok;
	}

	/**
	 * Validate notBefore
	 *
	 * @access	private
	 * @return	bool
	 */
	private function notBefore()
	{
		if (!isset($this->jwt->payload->nbf)) {
			return true;
		}
		$ok = time() + 1 >= $this->jwt->payload->nbf;
		if (!$ok) {
			$this->message = $this->messages['NOTBEFORE'];
		}
		return $ok;
	}

	/**
	 * Validate expiration
	 *
	 * @access	private
	 * @return	bool
	 */
	private function expires()
	{
		if (!isset($this->jwt->payload->exp) || $this->jwt->payload->exp == 0) {
			return true;
		}
		$ok = time() <= $this->jwt->payload->exp;
		if (!$ok) {
			$this->message = $this->messages['EXPIRED'];
		}
		return $ok;
	}

	/**
	 * Validate all together
	 *
	 * @access	public
	 * @return	bool
	 */
	public function validate()
	{
		return $this->structure() &&
			$this->signature() &&
			$this->notBefore() &&
			$this->expires();
	}

	/**
	 * Returns validation error message
	 *
	 * @access	public
	 * @return	string
	 */
	public function getMessage()
	{
		return $this->message;
	}
}
