<?php

namespace innsert\lib;

/**
 * Innsert PHP MVC Framework
 *
 * Two way encryption class
 *
 * @author	isaac
 * @package	innsert
 * @version	1
 */
class Encryption
{
	/**
	 * String to encrypt
	 *
	 * @access	public
	 * @var		string
	 */
	public $token;

	/**
	 * Cipher method with default value if none given
	 *
	 * @access	public
	 * @var		string
	 */
	public $cipher = 'AES-256-CBC';

	/**
	 * Encryption passphrase (secret) with default value if none given
	 *
	 * @access	public
	 * @var		string
	 */
	public $secret;

	/**
	 * Token separator
	 *
	 * @access	public
	 * @var		string
	 */
	public $separator = '::';

	/**
	 * Constructor
	 *
	 * Initialize values
	 *
	 * @access	public
	 * @param   string  $token  String to encrypt or decrypt
	 * @param	string	$secret Encryption secret
	 * @param   string  $cipher Cipher method
	 */
	public function __construct($token, $secret, $cipher = null)
	{
		$this->token = $token;
		$this->secret = $secret;
		if (isset($cipher)) {
			$this->cipher = $cipher;
		}
	}

	/**
	 * Generates random iv
	 *
	 * @access  public
	 * @return  string
	 */
	public function randomIv()
	{
		return openssl_random_pseudo_bytes(
			openssl_cipher_iv_length($this->cipher)
		);
	}

	/**
	 * Encrypts string
	 *
	 * @access	public
	 * @param	mixed	$iv     Inizialization vector
	 * @return 	string
	 */
	public function encrypt($iv)
	{
		return openssl_encrypt(
			$this->token,
			$this->cipher,
			$this->secret,
			0,
			$iv
		) .
			$this->separator .
			bin2hex($iv);
	}

	/**
	 * Decrytps token
	 *
	 * @access	public
	 * @return 	string
	 */
	public function decrypt()
	{
		list($token, $iv) = explode($this->separator, $this->token);
		return openssl_decrypt(
			$token,
			$this->cipher,
			$this->secret,
			0,
			hex2bin($iv)
		);
	}
}
