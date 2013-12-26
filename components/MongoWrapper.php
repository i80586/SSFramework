<?php

/**
 * Wrapper for Mongo
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
class MongoWrapper
{
	/**
	 *
	 * @var type 
	 */
	private $_mongo = null;
	/**
	 * Database name
	 * @var string 
	 */
	private $_dbName;
	
	/**
	 * Class construction
	 */
	public function __construct(array $connection)
	{
		$this->_mongo = new MongoClient($connection['connectionString']);
		$this->_dbName = $connection['dbName'];
	}
	
	/**
	 * 
	 * @staticvar null $instance
	 * @return \self
	 */
	public static function instance()
	{
		static $instance = null;
		
		if(null === $instance) {
			$instance = new self;
		}
		
		return $instance;
	}
	
	/**
	 * Catch mongo methods
	 * @param string $method
	 * @param array $params
	 * @return mixed
	 */
	public function __call($method, $params)
	{
		return call_user_func_array(array($this->_mongo, $method), $params);
	}
	
	/**
	 * Catch mongo properties
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		return $this->_mongo->$name;
	}
	
	/**
	 * Get collection
	 * @param string $collectionName
	 * @return MongoCollection
	 */
	public function getCollection($collectionName)
	{
		return $this->_mongo->{$this->_dbName}->{$collectionName};
	}

}