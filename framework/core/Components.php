<?php

namespace SS;

/**
 * Components class
 * Uses for control core framework components
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
class Components
{
	/**
	 * @var type
	 */
	protected static $_componentsList = array();
	/**
	 * Components list
	 * @var array
	 */
	protected static $_components = array();
	
	/**
	 * Get component. If not exists, create it
	 * @param string $name
	 * @return object
	 */
	public static function getComponent($name, $arguments) 
	{
		$className = 'SS\\' . $name;
		
		if (!isset(self::$_componentsList[$className])) {
			self::registerComponent($className, $arguments);
			self::createComponent($className);
		}
		
		return self::$_componentsList[$className];
	}
	
	/**
	 * Create component
	 * @param string $name
	 */
	protected static function createComponent($name)
	{
		if (!isset(self::$_components[$name])) {
			throw new Exception('Undefined component: ' . $name);
		}

		$reflectionClass = new \ReflectionClass($name);
		self::$_componentsList[$name] = empty(self::$_components[$name]) ?
											$reflectionClass->newInstance() : 
											$reflectionClass->newInstance(self::$_components[$name]);
	}
	
	/**
	 * Register new component
	 * @param string $name
	 * @param array $params
	 */
	protected static function registerComponent($name, array $params = array())
	{
		self::$_components[$name] = $params;
	}
	
}