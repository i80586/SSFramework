<?php

namespace framework\components;

use framework\core\Exception;
use framework\core\App;

/**
 * Class Database
 * Simple wrapper for PDO
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 2 January 2013
 */
class Database extends \framework\core\BaseComponent
{

    /**
     * PDO handler
     * 
     * @var \PDO 
     */
    private $_pdoHandler = null;

    /**
     * Current PDO statement
     * 
     * @var \PDOStatement
     */
    private $_pdoStatement = false;

    /**
     * Default fetch mode
     * 
     * @var integer 
     */
    private $_defaultFetchMode = \PDO::FETCH_ASSOC;

    /**
     * Class construction
     * 
     * @throws \framework\core\Exception
     */
    public function __construct()
    {
        if (null === $this->_pdoHandler) {
            if (null === ($dbConfig = App::$get->config('db'))) {
                throw new Exception('Database connection is not set');
            }

            $this->connect($dbConfig);
        }
    }

    /**
     * Connect to database
     * 
     * @param array $config
     */
    protected function connect(array $config)
    {
        $initParams = [];
        
        if (isset($config['encoding'])) {
            $initParams =  [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $config['encoding']];
        }
        
        $this->_pdoHandler = new \PDO($config['dsn'], $config['username'], $config['password'], $initParams);
        $this->_pdoHandler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->_pdoHandler->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    }

    /**
     * Quote value
     * 
     * @param mixed $value
     * @return mixed
     */
    public function quoteValue($value)
    {
        if (is_int($value) || is_float($value)) {
            return $value;
        }

        if (false !== ($value = $this->_pdoHandler->quote($value))) {
            return $value;
        } else {
            return "'" . addcslashes(str_replace("'", "''", $value), "\000\n\r\\\032") . "'";
        }
    }

    /**
     * Apply params to string
     * 
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
     * 
     * @throws \framework\core\Exception
     */
    private function checkStatement()
    {
        if (false === $this->_pdoStatement) {
            throw new Exception('Statement is invalid');
        }
        
        $this->_pdoStatement->execute();
    }

    /**
     * Set query and execute
     * 
     * @param string $query
     * @param array $params
     * @param boolean $execute
     * @return Database;
     */
    public function setQuery($query, array $params = [], $execute = true)
    {
        $this->_pdoStatement = $this->_pdoHandler->prepare($query);

        if ($execute) {
            $this->_pdoStatement->execute($params);
        }

        return $this;
    }

    /**
     * Get record
     * 
     * @param integer $fetchType
     * @return array|false
     */
    public function getOne()
    {
        $this->checkStatement();
        return $this->_pdoStatement->fetch($this->_defaultFetchMode);
    }

    /**
     * Get all records
     * 
     * @return array
     */
    public function getAll()
    {
        $this->checkStatement();
        return $this->_pdoStatement->fetchAll($this->_defaultFetchMode);
    }

    /**
     * Insert new row into table
     * 
     * @param string $tableName
     * @param array $columns
     * @return boolean|integer
     */
    public function insert($tableName, array $columns, $ignore = false)
    {
        $fields = '`' . implode('`,`', array_keys($columns)) . '`';
        $values = implode(',', array_map(function($value) {
                                return $this->quoteValue($value);
                            },
                            array_values($columns)));

        $queryString = 'INSERT';
        if ($ignore) {
            $queryString .= ' IGNORE';
        }
        $queryString .= ' INTO :table (:columns) VALUES (:values)';

        $query = $this->applyParams($queryString, [
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
     * Catch called method. Call method from PDO statement
     * 
     * @param string $name
     * @param array $arguments
     * @return void|mixed
     */
    public function __call($name, array $arguments = [])
    {
        if ($this->_pdoStatement) {
            return call_user_func_array([$this->_pdoStatement, $name], $arguments);
        }
    }

}
