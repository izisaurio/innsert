<?php

namespace innsert\mvc;

/**
 * Innsert PHP MVC Framework
 *
 * Interface for a Model to use for authentication
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
interface AuthModel
{
	/**
	 * Membership model data for http sessions
	 *
	 * @access	public
	 * @return	array
	 */
	public function sessMembershipData();

	/**
	 * Permissions for http sessions
	 *
	 * @access	public
	 * @return	array
	 */
	public function findSessUserPermissions();

	/**
	 * Membership model data for jwt tokens
	 *
	 * @access	public
	 * @return	array
	 */
	public function jwtMembershipData();

	/**
	 * Permissions for jwt tokens
	 *
	 * @access	public
	 * @return	array
	 */
	public function findJwtUserPermissions();
}
