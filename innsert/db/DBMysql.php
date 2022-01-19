<?php

namespace innsert\db;

use \PDO,
	\PDOStatement,
	\PDOException,
	innsert\core\Config,
	innsert\core\Log;

/**
 * Innsert PHP MVC Framework
 *
 * Default mysql connection class
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class DBMysql extends Config implements DBInterface
{
	/**
	 * Configuration file path
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $_path = ['app', 'configs', 'mysql'];

	/**
	 * PDO connection
	 *
	 * @access	protected
	 * @var		PDO
	 */
	protected $connection;

	/**
	 * Statement
	 *
	 * @access	protected
	 * @var		PDOStatement
	 */
	protected $statement;

	/**
	 * Current object queries executed log
	 *
	 * @access	public
	 * @var		array
	 */
	public $queries = [];

	/**
	 * Log queries to log file flag
	 *
	 * @access	public
	 * @var		bool
	 */
	public $logQueries = false;

	/**
	 * Translates DBMapper rule type to PDO param type
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $dictionary = [
		'INT'		=> PDO::PARAM_INT,
		'NUMERIC'	=> PDO::PARAM_STR,
		'DECIMAL'	=> PDO::PARAM_STR,
		'STRING'	=> PDO::PARAM_STR,
		'TEXT'		=> PDO::PARAM_STR,
		'ALPHA'		=> PDO::PARAM_STR,
		'DATETIME'	=> PDO::PARAM_STR,
		'DATE'		=> PDO::PARAM_STR,
		'TIME'		=> PDO::PARAM_STR,
		'TIMESTAMP'	=> PDO::PARAM_INT,
		'BOOL'		=> PDO::PARAM_BOOL,
		'EMAIL'		=> PDO::PARAM_STR
	];

	/**
	 * Constructor
	 *
	 * Creates the connection
	 *
	 * @access	public
	 * @throws	DatabaseConnectException
	 */
	public function __construct()
	{
		parent::__construct();
		$server = $this['server'];
		$database = $this['database'];
		$charset = $this['charset'];
		try {
			$this->connection = new PDO(
				"mysql:dbname={$database};host={$server}",
				$this['user'],
				$this['password'],
				[PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$charset}"]
			);
		} catch (PDOException $ex) {
			throw new DatabaseConnectException($ex->getCode());
		}
		if (DEBUG) {
			$this->logQueries = true;
		}
	}

	/**
	 * Destructor
	 *
	 * Closes database
	 *
	 * @access	public
	 */
	public function __desctruct()
	{
		$this->statement = $this->connection = null;
	}

	/**
	 * Search query
	 *
	 * @access	public
	 * @param	string	$query		Search query sentence
	 * @param	Params	$params		Params added to sentence
	 * @return	array
	 * @throws	DatabaseStatementException
	 */
	public function search($query, Params $params = null)
	{
		$this->processStatement($query, $params);
		$results = $this->statement->fetchAll(PDO::FETCH_ASSOC);
		if ($results === false) {
			throw new DatabaseStatementException(join(';', $this->statement->errorInfo()), $query);
		}
		return $results;
	}

	/**
	 * Non search query execution
	 *
	 * @access	public
	 * @param	string	$query		Non search query sentence
	 * @param	Params	$params		Params added to sentence
	 * @throws	DatabaseStatementException
	 */
	public function execute($query, Params $params = null)
	{
		$this->processStatement($query, $params);
	}

	/**
	 * Process a statement
	 *
	 * @access	public
	 * @param	string	$query		Query sentence
	 * @param	Params	$params		Params added to sentence
	 * @throws	DatabaseStatementException
	 */
	public function processStatement($query, Params $params = null)
	{
		$this->queries[] = $query;
		if ($this->logQueries) {
			Log::add('queries', $query);
		}
		if (isset($params)) {
			$this->prepareStatement($query);
			$this->bindParams($params);
			$this->executeStatement();
		} else {
			$this->statement = $this->connection->query($query);
			if ($this->statement === false) {
				throw new DatabaseStatementException(join(';', $this->connection->errorInfo()), $query);
			}
		}
	}

	/**
	 * Creates a prepared statement
	 *
	 * @access	public
	 * @param	string	$query		uery sentence
	 * @throws	DatabaseStatementException
	 */
	public function prepareStatement($query)
	{
		$this->statement = $this->connection->prepare($query);
		if ($this->statement === false) {
			throw new DatabaseStatementException('Statement prepare error', $query);
		}
	}

	/**
	 * Add params to prepared sentence
	 *
	 * @access	public
	 * @param	Params	$params		Params to add
	 * @throws	DatabaseStatementException
	 */
	public function bindParams(Params $params)
	{
		foreach ($params as $key => $param) {
			if (!$this->statement->bindValue(($key + 1), $param->value, $this->dictionary[$param->attr])) {
				throw new DatabaseStatementException(join(';', $this->statement->errorInfo()), end($this->queries));
			}
		}
	}

	/**
	 * Executes a prepared statement
	 *
	 * @access	public
	 * @throws	DatabaseStatementException
	 */
	public function executeStatement()
	{
		if (!$this->statement->execute()) {
			throw new DatabaseStatementException(join(';', $this->statement->errorInfo()), end($this->queries));
		}
	}

	/**
	 * Returns last inserted id
	 *
	 * @access	public
	 * @return	int
	 */
	public function lastId()
	{
		return $this->connection->lastInsertId();
	}

	/**
	 * Escapes a query value
	 *
	 * @access	public
	 * @param	mixed	$value		Value to clean
	 * @return	string
	 */
	public function clean($value)
	{
		return $this->connection->quote($value);
	}

	/**
	 * Begins a transaction
	 *
	 * @access	public
	 * @return	bool
	 */
	public function beginTransaction()
	{
		return $this->connection->beginTransaction();
	}

	/**
	 * Commits current transaction
	 *
	 * @access	public
	 * @return	bool
	 */
	public function commit()
	{
		return $this->connection->commit();
	}

	/**
	 * Rollback to current transaction
	 *
	 * @access	public
	 * @return	bool
	 */
	public function rollback()
	{
		return $this->connection->rollback();
	}
}