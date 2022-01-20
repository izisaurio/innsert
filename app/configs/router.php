<?php

/**
 * Innsert PHP MVC Framework
 *
 * Router config file
 *
 * @author  izisaurio
 * @package innsert/framework
 * @version 1
 */
return [
	'/' => 'start/index',

	'login' => 'user/auth/login',

	'/admin/:controller' => 'admin/:controller/index',

	'/admin/:controller/:action' => 'admin/:controller/:action',

	'/admin/:controller/:action/:id' => 'admin/:controller/:action/:id',
];
