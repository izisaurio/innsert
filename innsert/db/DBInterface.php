<?php

namespace innsert\db;

/**
 * Innsert PHP MVC Framework
 *
 * Interface for database connection classes
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
interface DBInterface
{
	/**
	 * Search query
	 *
	 * @access	public
	 * @param	string	$query		Search query sentence
	 * @param	Params	$params		Params added to sentence
	 * @return	array
	 */
	public function search($query, Params $params = null);

	/**
	 * Non search query execution
	 *
	 * @access	public
	 * @param	string	$query		Non search query sentence
	 * @param	Params	$params		Params added to sentence
	 */
	public function execute($query, Params $params = null);

	/**
	 * Process a statement
	 *
	 * @access	public
	 * @param	string	$query		Query sentence
	 * @param	Params	$params		Params added to sentence
	 */
	public function processStatement($query, Params $params = null);

	/**
	 * Creates a prepared statement
	 *
	 * @access	public
	 * @param	string	$query		Query sentence
	 */
	public function prepareStatement($query);

	/**
	 * Add params to prepared sentence
	 *
	 * @access	public
	 * @param	Params	$params		Params to add
	 */
	public function bindParams(Params $params);

	/**
	 * Executes a prepared statement
	 *
	 * @access	public
	 */
	public function executeStatement();

	/**
	 * Returns last inserted id
	 *
	 * @access	public
	 * @return	int
	 */
	public function lastId();

	/**
	 * Escapes a query value
	 *
	 * @access	public
	 * @param	mixed	$value	Value to clean
	 * @return	string
	 */
	public function clean($value);

	/**
	 * Begins a transaction
	 *
	 * @access	public
	 * @return	bool
	 */
	public function beginTransaction();

	/**
	 * Commits current transaction
	 *
	 * @access	public
	 * @return	bool
	 */
	public function commit();

	/**
	 * Rollback to current transaction
	 *
	 * @access	public
	 * @return	bool
	 */
	public function rollback();
}