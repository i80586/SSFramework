<?php

namespace SS;

/**
 * Class Database
 * Simple wrapper for PDO
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 2 January 2013
 */
class Database
{
	/**
	 * PDO handler
	 * @var \PDO 
	 */
	private $_pdoHandler = null;
	
	/**
	 * Current PDO statement
	 * @var \PDOStatement
	 */
	private $_pdoStatement = false;
	
	/**
	 * Default fetch mode
	 * @var integer 
	 */
	private $_defaultFetchMode = \PDO::FETCH_ASSOC;
	
	/**
	 * Class construction
	 * @throws Exception
	 */
	public function __construct()
	{
		if (null === $this->_pdoHandler) {
			$dbConfig = isset(Application::getConfig()['db']) ? Application::getConfig()['db'] : null;
			
			if (null === $dbConfig) {
				throw new Exception('Database connection is not set');
			}
			
			$this->_pdoHandler = new \PDO($dbConfig['dsn'], $dbConfig['username'], $dbConfig['password'], array(
				\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $dbConfig['encoding']
			));
			
			$this->_pdoHandler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$this->_pdoHandler->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		}
	}
	
	/**
	 * Quote value
	 * @param mixed $value
	 * @return mixed
	 */
	public function quoteValue($value)
	{
		if (is_int($value) || is_float($value)) {
			return $value;
		}
		
		if (false !== ($value=$this->_pdoHandler->quote($value))) {
			return $value;
		} else {
			return "'" . addcslashes(str_replace("'", "''", $value), "\000\n\r\\\032") . "'";
		}
	}
	
	/**
	 * Apply params to string
	 * @param string $string
	 * @param array $params
	 * @return string
	 */
	private function applyParams($string, array $params)
	{
		return str_replace(array_keys($params), array_values($params), $string);
	}
	
	/**
	 * Check for PDO statement
	 * @throws Exception
	 */
	private function checkStatement()
	{
		if (false === $this->_pdoStatement) {
			throw new Exception('Statement is invalid');
		} else {
			$this->_pdoStatement->execute();
		}
	}
	
	/**
	 * Run query
	 * @param type $query
	 * @param type $params
	 */
	public function setQuery($query, array $params = array(), $execute = true)
	{
		$this->_pdoStatement = $this->_pdoHandler->prepare($query);
		
		if ($execute) {
			$this->_pdoStatement->execute($params);
		}
		
		return $this;
	}
	
	/**
	 * Get record
	 * @param integer $fetchType
	 * @return array
	 */
	public function get()
	{
		$this->checkStatement();
		return $this->_pdoStatement->fetch($this->_defaultFetchMode);
	}
	
	/**
	 * Get all records
	 * @return array
	 */
	public function getAll()
	{
		$this->checkStatement();
		return $this->_pdoStatement->fetchAll($this->_defaultFetchMode);
	}
	
	/**
	 * Insert new row into table
	 * @param string $tableName
	 * @param array $columns
	 * @return mixed
	 */
	public function insert($tableName, array $columns)
	{
		$fields = '`' . implode('`,`', array_keys($columns)) . '`';
		$values = implode(',', array_map(function($value) {
											return $this->quoteValue($value); 
										}, array_values($columns)));
		
		$query = $this->applyParams("INSERT INTO :table (:columns) VALUES (:values)", [
			':table' => '`' . $tableName . '`',
			':columns' => $fields,
			':values' => $values
		]);
				
		$this->_pdoStatement = $this->_pdoHandler->prepare($query);
		
		if (false === $this->_pdoStatement->execute()) {
			return false;
		}
			
		return $this->_pdoStatement->rowCount();
	}
		
	/**
	 * Catch called method
	 * @param string $name
	 * @param array $arguments
	 */
	public function __call($name, $arguments)
	{
		if (false !== $this->_pdoStatement) {
			return call_user_func_array(array($this->_pdoStatement, $name), $arguments);
		}
	}

}