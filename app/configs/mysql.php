<?php

/**
 * Innsert PHP MVC Framework
 *
 * Opciones de configuración de la base de datos default.
 * Para usar mútiples genera un nuevo archivo de configuración y
 * una clase heredada de innsert/db/DBMysql que apunte a ese nuevo archivo
 *
 * @author	izisaurio
 * @package	innsert/framework
 * @version	1
 */
return [
	/**
	* El nombre o dirección del servidor de base de datos
	*
	* @var	string
	*/
	'server' => 'localhost',

	/**
	* El nombre de usuario con acceso a la base de datos
	*
	* @var	string
	*/
	'user' => 'user',

	/**
	* La contraseña del usuario
	*
	* @var	string
	*/
	'password' => '###',

	/**
	* El nombre de la base de datos en la que se trabajará
	*
	* @var	string
	*/
	'database' => 'defaultDatabase',

	/**
	* El charset por defecto con el que se trabajá
	*
	* @var	string
	*/
	'charset' => 'utf8mb4'
];