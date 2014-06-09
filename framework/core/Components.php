<?php

namespace framework\core;

/**
 * Components class
 * Uses for control core and user components
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
class Components
{

	/**
	 * Components list
	 * 
	 * @var array
	 */
	protected static $_componentsList = [];

	/**
	 * Components paths list
	 * 
	 * @var array
	 */
	protected static $_components = [];

	/**
	 * Get component. Create new if not exists
	 * 
	 * @param string $name
	 * @param array $arguments
	 * @return object
	 */
	public static function getComponent($name, array $arguments)
	{
		if (!isset(self::$_componentsList[$classPath = self::getComponentPath($name)])) {
			self::registerComponent($classPath, $arguments);
			self::createComponent($name, $classPath);
		}
		return self::$_componentsList[$classPath];
	}

	/**
	 * Create component
	 * 
	 * @param string $name
	 * @param string $path
	 * @throws \framework\core\Exception
	 */
	protected static function createComponent($name, $path)
	{
		if (!isset(self::$_components[$path])) {
			throw new \framework\core\Exception('Undefined component: ' . $name);
		}

		try {
			$reflectionClass = new \ReflectionClass($path);
		} catch (\ReflectionException $e) {
			throw new Exception('Component <b>:c</b> doesn\'t exists at path <b>:p</b>', [
				':c' => $name,
				':p' => $path
			]);
		}

		self::$_componentsList[$path] = empty(self::$_components[$path]) ?
				$reflectionClass->newInstance() :
				$reflectionClass->newInstance(self::$_components[$path]);
	}

	/**
	 * Register new component
	 * 
	 * @param string $name
	 * @param array $params
	 */
	protected static function registerComponent($name, array $params = [])
	{
		self::$_components[$name] = $params;
	}

	/**
	 * Get component path
	 * 
	 * @param string $name
	 * @return string
	 */
	protected static function getComponentPath($name)
	{
		if (!isset(Application::getConfig()['components']) || !isset(Application::getConfig()['components'][$name])) {
			return '\framework\components\\' . ucfirst($name);
		}
		return Application::getConfig()['components'][$name]['path'];
	}

}
