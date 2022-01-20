<?php

namespace innsert\sess;

use innsert\resp\Redirect,
	innsert\resp\JsonResponse,
	innsert\resp\Response,
	innsert\core\Defaults;

/**
 * Innsert PHP MVC Framework
 *
 * Base class for memberships using http sessions
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
abstract class Membership
{
	/**
	 * Current membership status
	 *
	 * @access	public
	 * @var		bool
	 */
	public $status = false;

	/**
	 * User data in membership
	 *
	 * @access	public
	 * @var		stdClass
	 */
	public $data;

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
	 * Clear session when membership authorization fails
	 *
	 * @access	public
	 * @var		bool
	 */
	public $purge = false;

	/**
	 * Prefix for membership session values
	 *
	 * @access	public
	 * @var		string
	 */
	public $sessionKey = '__membership__';

	/**
	 * Session to use
	 *
	 * @access	public
	 * @var		Session
	 */
	public $session;

	/**
	 * Constructor
	 *
	 * Sets session and validates status
	 *
	 * @access	public
	 * @param	Session	$session	If null uses default session instance
	 */
	public function __construct(Session $session = null)
	{
		$this->session = isset($session) ? $session : Sess::defaultInstance();
		if (isset($this->session[$this->sessionKey])) {
			if (!$this->sessionValidations()) {
				$this->close();
			} else {
				$this->status = true;
				$this->data = (object) $this->session[$this->sessionKey];
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
		$this->notAuthorizedResponse = $response;
		return $this;
	}

	/**
	 * Sets purge session value
	 *
	 * @access	public
	 * @param	bool	$purge	Purge value
	 * @return	Membership
	 */
	public function setPurgeOnNotAuthorized($purge)
	{
		$this->purge = $purge;
		return $this;
	}

	/**
	 * Starts Membership
	 *
	 * @access	public
	 * @param	Authentication	$auth	Authentication instance
	 * @return	bool
	 */
	public function start(Authentication $auth)
	{
		if (!$auth->validate()) {
			return false;
		}
		$this->session[$this->sessionKey] = $auth->authData();
		$this->status = true;
		$this->data = (object) $this->session[$this->sessionKey];
		return true;
	}

	/**
	 * Edits a membership session value
	 *
	 * @access	public
	 * @param	mixed	$key	Key to edit|Array to append and/or edit
	 * @param	mixed	$value	Valur for single element
	 */
	public function edit($key, $value = null)
	{
		if (is_array($key)) {
			$this->session[$this->sessionKey] = array_merge($this->data, $key);
		} else {
			$this->session[$this->sessionKey] = array_merge(
				(array) $this->data,
				[$key => $value]
			);
		}
	}

	/**
	 * Closes session
	 *
	 * @access	public
	 */
	public function close()
	{
		unset($this->session[$this->sessionKey]);
		$this->status = false;
	}

	/**
	 * Checks current session authenticity
	 *
	 * @access	public
	 * @param	mixed	$permissions	Permissions to check
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
			$permissions = is_array($permissions)
				? $permissions
				: [$permissions];
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
	 * Alias of above
	 *
	 * @access	public
	 * @param	mixed	$permissions	Permissions to check (can be empty)
	 * @return	Membership
	 * @throws	MembershipParamNotFound
	 */
	public function authenticateOrRedirect($permission = null)
	{
		return $this->authenticateAndRedirect($permission);
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

	/**
	 * Additional membership validations
	 *
	 * @access	protected
	 * @return	bool
	 */
	abstract protected function sessionValidations();
}
