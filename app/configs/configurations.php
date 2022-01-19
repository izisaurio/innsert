<?php

/**
 * Innsert PHP MVC Framework
 *
 * Main project configs
 *
 * @author	izisaurio
 * @package	innsert/framework
 * @version	1
 */
return [
	/**
	* Project domain
	*
	* @var	string
	*/
	'domain' => 'http://localhost',

	/**
	* Rewrite engine is on
	*
	* @var	bool
	*/
	'rewrites' => true,

	/**
	* Error reporting value
	*
	* @var	int
	*/
	'reporting' => E_ALL,

	/**
	* Directory separator
	*
	* @var	string
	*/
	'ds' => '/',

	/**
	* Debug mode
	*
	* @var	bool
	*/
	'debug' => true,

	/**
	* Default timezone
	*
	* @var	string
	*/
	'timezone' => 'America/Monterrey',

	/**
	* Default locale
	*
	* @var	string
	*/
	'locale' => 'en-us',

	/**
	* Default language
	*
	* @var	string
	*/
	'language' => 'es',

	/**
	* Available languages
	*
	* @var	array
	*/
	'languages' => ['es', 'en'],

	/**
	* Validate ip in http sessions
	*
	* @var	bool
	*/
	'sessionCheckIP' => false
];