<?php

namespace innsert\sess;

use innsert\mvc\AuthModel, innsert\lib\Request;

/**
 * Innsert PHP MVC Framework
 *
 * Base class for authentication fot http sessions
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class Authentication
{
	/**
	 * AuthModel to authenticate
	 *
	 * @access	public
	 * @var		AuthModel
	 */
	protected $model;

	/**
	 * Data to be send to membership
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $data = [];

	/**
	 * FCalls the user permission on AuthModel
	 *
	 * @access	protected
	 * @return	Authentication
	 */
	protected function findUserPermissions()
	{
		$this->data['permissions'] = $this->model->findSessUserPermissions();
		return $this;
	}

	/**
	 * Calls data array to be send to model membership on AuthModel
	 *
	 * @access	protected
	 * @return	Authentication
	 */
	protected function membershipData()
	{
		$data = $this->model->sessMembershipData();
		$this->data = array_merge($data, $this->membershipValidationData());
		return $this;
	}

	/**
	 * Additional data to be send to Membership
	 *
	 * @access	private
	 * @return	array
	 */
	private function membershipValidationData()
	{
		$request = Request::defaultInstance();
		return [
			'time' => time(),
			'address' => $request->server('REMOTE_ADDR'),
			'agent' => $request->server('HTTP_USER_AGENT'),
		];
	}

	/**
	 * Sets AuthModel
	 *
	 * @access	public
	 * @param	AuthModel	$model	Model to authenticate
	 * @return	Authentication
	 */
	public function setModel(AuthModel $model)
	{
		$this->model = $model;
		$this->membershipData()->findUserPermissions();
		return $this;
	}

	/**
	 * Simple validation, use DBAuthentication or extend to create your own
	 *
	 * @access	public
	 * @return	bool
	 */
	public function validate()
	{
		return isset($this->model) &&
			$this->model instanceof AuthModel &&
			isset($this->data);
	}

	/**
	 * Returns AuthModel
	 *
	 * @access	public
	 * @return	Model
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * Returns data to be send to membership, extend to transform data like encryption
	 *
	 * @access	public
	 * @return	array
	 */
	public function authData()
	{
		return $this->data;
	}
}
