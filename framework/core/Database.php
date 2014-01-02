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
		}
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
		$this->_pdoStatement->fetchAll($this->_defaultFetchMode);
	}
		
	/**
	 * Catch called method
	 * @param string $name
	 * @param array $arguments
	 */
	public function __call($name, $arguments)
	{
		if (false !== $this->_pdoStatement) {
			call_user_func_array(array($this->_pdoStatement, $name), $arguments);
		}
	}

}