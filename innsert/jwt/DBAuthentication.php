<?php

namespace innsert\jwt;

use \DateTime,
	\DateInterval,
	innsert\db\DBMapper,
	innsert\db\Params,
	innsert\lib\StringFunctions;

/**
 * Innsert PHP MVC Framework
 *
 * Database authentication for jwt memberships
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class DBAuthentication extends Authentication
{
	/**
	 * Database table mapper
	 *
	 * @access	public
	 * @var		DBMapper
	 */
	public $mapper;

	/**
	 * Username to validate
	 *
	 * @access	public
	 * @var		string
	 */
	public $username;

	/**
	 * User password
	 *
	 * @access	public
	 * @var		string
	 */
	public $password;

	/**
	 * Username column on database
	 *
	 * @access	public
	 * @var		string
	 */
	public $column = 'correo';

	/**
	 * Property on AuthModel with the password
	 *
	 * @access	public
	 * @var		string
	 */
	public $passProperty = 'password';

	/**
	 * Property on AuthModel with the salt
	 *
	 * @access	public
	 * @var		string
	 */
	public $saltProperty = 'salt';

	/**
	 * Constructor
	 *
	 * Receives mapper, username and password
	 *
	 * @access	public
	 * @param	DBMapper	$mapper		Database mapper
	 * @param	string		$username	Username to search
	 * @param	string		$password	Password to validate
	 */
	public function __construct(DBMapper $mapper, $username, $password)
	{
		$this->mapper = $mapper;
		$this->username = $username;
		$this->password = $password;
	}

	/**
	 * Validate credentials
	 *
	 * @access	public
	 * @return	bool
	 */
	public function validate()
	{
		$params = new Params();
		$params->add('STRING', $this->username);
		$user = $this->mapper
			->where($this->column, '?')
			->limit(1)
			->find($params)
			->first();
		if (!$user) {
			return false;
		}
		$now = (new DateTime())->add(new DateInterval('PT15S'));
		$try = DateTime::createFromFormat('YmdHis', $user->intento);
		if ($try > $now) {
			$user->intento = (new DateTime())->format('YmdHis');
			$user->save(false);
			return false;
		}
		if (
			!StringFunctions::validatePassword(
				$this->password,
				$user->{$this->saltProperty},
				$user->{$this->passProperty}
			)
		) {
			return false;
		}
		$this->setModel($user);
		return parent::validate();
	}
}
