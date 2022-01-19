<?php

namespace innsert\jwt;
use innsert\lib\Encryption;

/**
 * Innsert PHP MVC Framework
 *
 * Object that provides tools for encryption and decryption of jwt payload
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Payload
{
	/**
	 * Encryption secret
	 *
	 * @access  private
	 * @var     string
	 */
	private $secret;

	/**
	 * Constructor
	 *
	 * Gets payload secret
	 *
	 * @access  public
	 * @param   string	$secreet	Encryption secret
	 */
	public function __construct($secret)
	{
		$this->secret = $secret;
	}

	/**
	 * Decrypts payload
	 * 
	 * @access	public
	 * @param	string	$data		Payload string to decrypt
	 * @return	array
	 */
	public function decrypt($data) {
		return json_decode((new Encryption($data->payload, $this->secret))->decrypt());
	}

	/**
	 * Encrypts payload
	 * 
	 * @access	public
	 * @param	array	$data		Payload data to encrypt
	 * @return	array
	 */
	public function encrypt(array $data) {
		$encryption = new Encryption(json_encode($data), $this->secret);
		return ['payload' => $encryption->encrypt($encryption->randomIv())];
	}
}