<?php

namespace innsert\jwt;

use	innsert\resp\Redirect,
	innsert\resp\Response,
	innsert\lib\StringFunctions,
	innsert\core\Defaults,
	\Closure;

/**
 * Innsert PHP MVC Framework
 *
 * Class for memberships using jwt tokens
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Membership
{
	/**
	 * Membership current status
	 *
	 * @access	public
	 * @var		bool
	 */
	public $status = false;

	/**
	 * Membership user data
	 *
	 * @access	public
	 * @var		stdClass
	 */
	public $data;

	/**
	 * Payload helper used when encrypting data
	 * 
	 * @access	public
	 * @var		Payload
	 */
	protected $payload;

	/**
	 * Redirect url on not authorized
	 *
	 * @access	public
	 * @var		string
	 */
	public $notAuthorizedURL;

	/**
	 * Response on not authorized
	 *
	 * @access	public
	 * @var		Response
	 */
	public $notAuthorizedResponse;

	/**
	 * Jwt token parsed
	 *
	 * @access	public
	 * @var		Parser
	 */
	public $jwt;

	/**
	 * Jwt default configs, extend class when change is needed
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $jwtConfigs = [
		'jsonTokenId'			=>	'not.used',
		'issuer'				=>	'innsert',
		'notBeforeInSeconds'	=>	0,
		'expiresInSeconds'		=>	86400 //1 day
	];

	/**
	 * Jwt validation error message
	 *
	 * @access	public
	 * @var		String
	 */
	public $errorMessage = 'Jwt';

	/**
	 * Jwt token secret
	 *
	 * @access	private
	 * @var		string
	 */
	protected $secret;

	/**
	 * Constructor
	 *
	 * Parses jwt token when received
	 *
	 * @access	public
	 * @param	string		$secret			Secret to protect jwt
	 * @param	string		$token			Jwt token string
	 * @param	Payload		$payload		Payload tools to encrypt data
	 */
	public function __construct($secret, $token = null, Payload $payload = null)
	{
		$this->secret = $secret;
		$this->payload = $payload;
		if (isset($token) && StringFunctions::startsWith($token, 'Bearer')) {
			$jwt = Jwt::parse($this->secret, $token);
			$validator = $jwt->validator();
			if (!$validator->validate()) {
				$this->errorMessage = $validator->getMessage();
				$this->close();
			} else {
				$this->jwt = $jwt;
				$this->status = true;		
				$data = isset($this->payload) ? $this->payload->decrypt($jwt->getPayloadData()) : $jwt->getPayloadData();
				$this->data = (object)$data;
			}
		}
	}

	/**
	 * Sets redirection url on not authorized
	 *
	 * @access	public
	 * @param	string	$url	Redirection url on not authorized
	 * @return	Membership
	 */
	public function setNotAuthorizedURL($url)
	{
		$this->notAuthorizedURL = $url;
		return $this;
	}

	/**
	 * Sets response on not authorized
	 *
	 * @access	public
	 * @param	Response	$response	Response on not authorizaed
	 * @return	Membership
	 */
	public function setNotAuthorizedResponse(Response $response)
	{
		$defaults = Defaults::defaultInstance();
		if (!empty($defaults['defaultHeaders'])) {
			$response->headers($defaults['defaultHeaders']);
		}
		$this->notAuthorizedResponse = $response;
		return $this;
	}

	/**
	 * Builds jwt and starts session
	 *
	 * @access	public
	 * @param	Authentication	$auth	Authentication instance
	 * @return	Builder
	 */
	public function start(Authentication $auth)
	{
		if (!$auth->validate()) {
			return;
		}
		$data = isset($this->payload) ? $this->payload->encrypt($auth->authData()) : $auth->authData();
		$builder = Jwt::build(
			$this->secret,
			$data,
			$this->jwtConfigs
		);
		$this->status = true;
		$this->data = (object)$builder->getPayloadData();
		return $builder;
	}

	/**
	 * Rebuilds Jwt
	 *
	 * @access	public
	 * @return	Builder
	 */
	public function rebuild()
	{
		$data = isset($this->payload) ? $this->payload->encrypt((array)$this->data) : (array)$this->data;
		return Jwt::build(
			$this->secret,
			$data,
			$this->jwtConfigs
		);
	}

	/**
	 * Closes current session
	 *
	 * @access	public
	 */
	public function close()
	{
		$this->status = false;
	}

	/**
	 * Checks user membership credentials
	 *
	 * @access	public
	 * @param	mixed	$permissions	Permissions to check (can be empty)
	 * @return	bool
	 */
	public function check($permissions = null)
	{
		if ($this->status && isset($this->data)) {
			if (!isset($permissions)) {
				return true;
			}
			if (!isset($this->data->permissions)) {
				return false;
			}
			$permissions = is_array($permissions) ? $permissions : [$permissions];
			$result = array_intersect($permissions, $this->data->permissions);
			return !empty($result);
		}
		return false;
	}

	/**
	 * Checks user membership credentials
	 *
	 * On fail redirects
	 *
	 * @access	public
	 * @param	mixed	$permissions	Permissions to check (can be empty)
	 * @return	Membership
	 * @throws	MembershipParamNotFound
	 */
	public function authenticateAndRedirect($permission = null)
	{
		if (!$this->check($permission)) {
			if (!isset($this->notAuthorizedURL)) {
				throw new MembershipParamNotFound('notAuthorizedURL');
			}
			return new Redirect($this->notAuthorizedURL);
		}
		return $this;
	}

	/**
	 * Checks user membership credentials
	 *
	 * On fails returns configured response
	 *
	 * @access	public
	 * @param	mixed	$permissions	Permissions to check (can be empty)
	 * @return	Membership
	 * @throws	MembershipParamNotFound
	 */
	public function authenticate($permission = null)
	{
		if (!$this->check($permission)) {
			if (!isset($this->notAuthorizedResponse)) {
				throw new MembershipParamNotFound('notAuthorizedResponse');
			}
			$this->notAuthorizedResponse->send();
		}
		return $this;
	}
}