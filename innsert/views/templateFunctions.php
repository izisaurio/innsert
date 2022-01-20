<?php

use innsert\lib\Request,
	innsert\lib\DatePlus,
	innsert\lang\Lang,
	innsert\sess\Sess,
	innsert\lib\StringFunctions;

/**
 * Innsert PHP MVC Framework
 *
 * Views helper functions
 *
 * @author  izisaurio
 * @package innsert
 * @version 1
 */

/**
 * Writes an array as html element attributes
 *
 * @param   array   $attrs  Attributes to write
 * @return  string
 */
function attrs(array $attrs)
{
	$list = '';
	foreach ($attrs as $name => $value) {
		$list .= is_int($name) ? "{$value} " : $name . '="' . $value . '" ';
	}
	return $list;
}

/**
 * Escapes a string
 *
 * @param   string  $data   String to escape
 * @return  string
 */
function e($data)
{
	return htmlspecialchars($data);
}

/**
 * Returns a DatePlus instance
 *
 * @param   string  $date   Date value as (Y-m-d H:i:s)
 * @return  DatePlus
 */
function datePlus($date = null)
{
	return isset($date) ? DatePlus::fromDB($date) : new DatePlus();
}

/**
 * Returns default session instance
 *
 * @return  Session
 */
function session()
{
	return Sess::defaultInstance();
}

/**
 * Returns a label
 *
 * @param   string  $message    Label key
 * @param   array   $params     Optional params of label message
 * @return  string
 */
function lang($message, array $params = [])
{
	return Lang::defaultInstance()->get($message, $params);
}

/**
 * Returns an HttpRequest GET key value
 *
 * @param   string  $key        GET key
 * @param   mixed   $default    Default value if key not found
 * @return  string
 */
function get($key, $default = null)
{
	return Request::defaultInstance()->get($key, $default);
}

/**
 * Returns an HttpRequest GET key from array value
 *
 * @param   string  $key        GET key
 * @param   int     $index      Array key
 * @param   mixed   $default    Default value if key not found
 * @return  string
 */
function getArray($key, $index, $default = null)
{
	$get = Request::defaultInstance()->get($key);
	if (!isset($get)) {
		return $default;
	}
	return isset($get[$index]) ? $get[$index] : $default;
}

/**
 * Returns an HttpRequest POST key value
 *
 * @param   string  $key        POST key
 * @param   mixed   $default    Default value if key not found
 * @return  string
 */
function post($key, $default = null)
{
	return Request::defaultInstance()->post($key, $default);
}

/**
 * Returns an HttpRequest POST key from array value
 *
 * @param   string  $key        POST key
 * @param   int     $index      Array key
 * @param   mixed   $default    Default value if key not found
 * @return  string
 */
function postArray($key, $index, $default = null)
{
	$post = Request::defaultInstance()->post($key);
	if (!isset($post)) {
		return $default;
	}
	return isset($post[$index]) ? $post[$index] : $default;
}

/**
 * Returns html of a "a" element (anchor, link)
 *
 * @param   string  $text   Anchor text
 * @param   string  $url    Anchor href
 * @param   array   $attrs  Attributes to write in element
 * @return  string
 */
function a($text, $url, array $attrs = [])
{
	return '<a href="' . $url . '" ' . attrs($attrs) . '>' . $text . '</a>';
}

/**
 * Returns html of a img element
 *
 * @param   string  $url    Image path
 * @param   string  $attrs  Attributes to write in element
 * @return  string
 */
function img($url, array $attrs = [])
{
	return '<img src="' . $url . '" ' . attrs($attrs) . ' />';
}

/**
 * Returns html of a input element
 *
 * @param   string  $type       Element type (text, password, submit, etc)
 * @param   string  $name       Element name
 * @param   string  $value      Element value
 * @param   array   $attrs      Attributes to write in element
 * @return  string
 */
function input($type, $name, $value = null, array $attrs = [])
{
	return '<input type="' .
		$type .
		'" name="' .
		$name .
		'" ' .
		(isset($value) ? 'value="' . $value . '" ' : '') .
		attrs($attrs) .
		'/>';
}

/**
 * Returns html of a input type text element
 *
 * @param   string  $name       Element name
 * @param   string  $value      Element value
 * @param   array   $attrs      Attributes to write in element
 * @return  string
 */
function text($name, $value = null, array $attrs = [])
{
	return input('text', $name, $value, $attrs);
}

/**
 * Returns html of a input type email element
 *
 * @param   string  $name       Element name
 * @param   string  $value      Element value
 * @param   array   $attrs      Attributes to write in element
 * @return  string
 */
function email($name, $value = null, array $attrs = [])
{
	return input('email', $name, $value, $attrs);
}

/**
 * Returns html of a input type password element
 *
 * @param   string  $name       Element name
 * @param   string  $value      Element value
 * @param   array   $attrs      Attributes to write in element
 * @return  string
 */
function password($name, $value = null, array $attrs = [])
{
	return input('password', $name, $value, $attrs);
}

/**
 * Returns html of a input type number element
 *
 * @param   string  $name       Element name
 * @param   string  $value      Element value
 * @param   array   $attrs      Attributes to write in element
 * @return  string
 */
function number($name, $value = null, array $attrs = [])
{
	return input('number', $name, $value, $attrs);
}

/**
 * Returns html of a hidden input with a csrf token
 *
 * @param   string  $name       Element name
 * @param   array   $attrs      Attributes to write in element
 * @return  string
 */
function csrf($name = 'token', array $attrs = [])
{
	$token = StringFunctions::uniqueString();
	$session = Sess::defaultInstance();
	$session[$name] = $token;
	return input('hidden', $name, $token, $attrs);
}

/**
 * Returns html of a textarea element
 *
 * @param   string  $name       Element name
 * @param   string  $value      Element value
 * @param   array   $attrs      Attributes to write in element
 * @return  string
 */
function textarea($name, $value = '', array $attrs = [])
{
	return '<textarea name="' .
		$name .
		'" ' .
		attrs($attrs) .
		'>' .
		$value .
		'</textarea>';
}

/**
 * Returns a select with options as key => value pair
 *
 * @param   string  $name           Select element name
 * @param   array   $options        Options collection
 * @param   mixed   $default        Default value
 * @param   mixed   $placeholder    Placeholder text when no value selected
 * @param   array   $attrs          Attributes to write in select
 */
function select(
	$name,
	$options,
	$default = null,
	$placeholder = null,
	array $attrs = []
) {
	$attrs['name'] = $name;
	if (isset($default) && !is_array($default)) {
		$default = [$default];
	}
	$select = '<select ' . attrs($attrs) . '>';
	if (isset($placeholder)) {
		$select .= "<option value='' class='select-default'>{$placeholder}</option>";
	}
	foreach ($options as $key => $option) {
		$selected =
			isset($default) && in_array($key, $default) ? ' selected' : '';
		$select .= "<option value='{$key}'{$selected}>{$option}</option>";
	}
	return "{$select}</select>";
}

/**
 * Returns a select with option values parsed of labels array
 *
 * @param   string  $name           Select element name
 * @param   array   $options        Options collection
 * @param   array   $labels         Array of labels
 * @param   mixed   $default        Default value
 * @param   mixed   $placeholder    Placeholder text when no value selected
 * @param   array   $attrs          Attributes to write in select
 * @return  string
 */
function selectWithLabelsArray(
	$name,
	$options,
	array $labels,
	$default = null,
	$placeholder = null,
	array $attrs = []
) {
	$results = [];
	foreach ($options as $key => $value) {
		$results[$key] = $labels[$value];
	}
	return select($name, $results, $default, $placeholder, $attrs);
}
