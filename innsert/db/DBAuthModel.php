<?php

namespace innsert\db;

use innsert\mvc\AuthModel;

/**
 * Innsert PHP MVC Framework
 *
 * Database mapper model with auth methods
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class DBAuthModel extends DBModel implements AuthModel
{
	/**
	 * Membership model data for http sessions
	 *
	 * @access	public
	 * @return	array
	 */
	public function sessMembershipData()
	{
		return [];
	}

	/**
	 * Permissions for http sessions
	 *
	 * @access	public
	 * @return	array
	 */
	public function findSessUserPermissions()
	{
		return [];
	}

	/**
	 * Membership model data for jwt tokens
	 *
	 * @access	public
	 * @return	array
	 */
	public function jwtMembershipData()
	{
		return [];
	}

	/**
	 * Permissions for jwt tokens
	 *
	 * @access	public
	 * @return	array
	 */
	public function findJwtUserPermissions()
	{
		return [];
	}
}
