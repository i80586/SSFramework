<?php

namespace SS;

/**
 * Class Model
 * Core class of all models
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 1 January 2013
 */
abstract class Model
{
	/**
	 * Attributes array
	 * @var array 
	 */
	public $attributes = [];
	
	/**
	 * Database handler
	 * @var SS\Database 
	 */
	private static $_dbHandler = null;
	
	/**
	 * Get attribute
	 * @param string $name
	 */
	public function __get($name)
	{
		if (!isset($this->attributes[$name])) {
			throw new Exception('Attribute <b>:a</b> not found in <b>:m</b> model', array(':a' => $name, ':m' => get_class($this)));
		}
		
		return $this->attributes[$name];
	}
	
	/**
	 * Apply attributes
	 * @param array $attributes
	 */
	public function applyAttributes(array $attributes)
	{
		$this->attributes = $attributes;
	}
	
	/**
	 * Returns database handler
	 * @return SS\Database
	 */
	public static function db()
	{
		if (null === self::$_dbHandler) {
			self::$_dbHandler = new Database();
		}
		
		return self::$_dbHandler;
	}

}