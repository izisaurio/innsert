<?php

namespace innsert\jwt;

use innsert\mvc\AuthModel;

/**
 * Innsert PHP MVC Framework
 *
 * Base class for authorization using jwt tokens
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Authentication
{
	/**
	 * Model that has the user data
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
	 * Calls the user permission on AuthModel
	 *
	 * @access	protected
	 * @return	Authentication
	 */
	protected function findUserPermissions()
	{
		$this->data['permissions'] = $this->model->findJwtUserPermissions();
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
		$this->data = $this->model->jwtMembershipData();
		return $this;
	}

	/**
	 * Sets the AuthModel
	 *
	 * @access	public
	 * @param	AuthModel	$model	AuthModel to be validated
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
