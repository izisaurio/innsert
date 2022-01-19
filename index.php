<?php

use innsert\core\Starter;

/**
 * Innsert PHP MVC Framework
 *
 * Innsert Framework requiere de PHP 5.5 o superior para su uso
 * Concentrador de peticiones
 *
 * @author	izisaurio
 * @package	innsert/framework
 * @version	1
 */
require('vendor/autoload.php');
$app = (new Starter(__FILE__))->load();
$app->respond();