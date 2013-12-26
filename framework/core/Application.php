<?php

namespace SS;

/**
 * Core class of Application
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
class Application
{
	/**
	 * @var array 
	 */
	public static $classmap = [];
	/**
	 * @var string 
	 */
	private static $_config;
	/**
	 * @var type 
	 */
	private static $_componentsList = array();
	/**
	 * Components list
	 * @var array
	 */
	private static $_components = array();
	
	/**
	 * Default autoload paths
	 * @var array 
	 */
	private static $_defaultAutoLoadPaths = array('/application/controllers', '/application/models');
	
	/**
	 * Class construction
	 * @param array $config
	 */
	public function __construct($config)
	{
		self::$_config = $config;
		$this->init();
	}
	
	/**
	 * Initialize function
	 */
	protected function init() 
	{
		// Register components
		foreach (self::$_config['components'] as $componentName => $componentParams) {
			if (is_int($componentName)) {
				$componentName = $componentParams;
			}
			self::registerComponent($componentName, is_array($componentParams) ? $componentParams : array());
		}
		
		spl_autoload_register('self::loadClasses');
	}
	
	/**
	 * Load classes
	 * @param string $class
	 * @return
	 */
	private static function loadClasses($class)
	{
		if (isset(self::$classmap[$class])) {
			include self::$classmap[$class];
			return;
		}
		
		foreach (self::$_defaultAutoLoadPaths as $path) {
			$requiredFile = BASE_PATH . $path . DIRECTORY_SEPARATOR . $class . '.php';
			
			if (is_file($requiredFile)) {
				include $requiredFile;
				return;
			}
		}
	}
	
	/**
	 * Parse route
	 * @return array
	 */
	private function parseRoute()
	{
		if (isset($_GET['r'])) {
			$route = preg_replace('/[^a-zA-Z\/]/', '', $_GET['r']);
			return (false === strpos($route, '/')) ? array($route, 'index') : explode('/', $route);
		}
		
		return array(self::$_config['app']['defaultController'], 'index');
	}
	
	/**
	 * Start web application
	 * @throws RException
	 */
	public function start()
	{
		list($controller, $action) = $this->parseRoute();
		
		$controllerClass = ucfirst($controller) . 'Controller';
		$actionName = 'on' . ucfirst($action);
		
		if (!is_file(BASE_PATH . '/application/controllers/' . $controllerClass . '.php')) {
			throw new RException(sprintf("Controller <b>%s</b> not found", $controllerClass)); 
		}
		
		$reflectionMethod = new \ReflectionMethod($controllerClass, $actionName);
		$reflactionClass = $reflectionMethod->getDeclaringClass();
		
		if (!$reflactionClass->isSubclassOf('Controller')) {
			// Exception
		}
		
		if (!$reflactionClass->hasMethod($actionName)) {
			throw new RException(sprintf("Action <b>%s</b> not found in <b>%s</b>", $action, $controllerClass)); 
		}
		
		$reflectionMethod->invoke($reflactionClass->newInstance());
	}
	
	/**
	 * Get resources url
	 * @return string
	 */
	public static function getStaticUrl()
	{
		return isset(self::$_config['app']['staticUrl']) ? self::$_config['app']['staticUrl'] : '/static';
	}
	
	/**
	 * Get component. If not exists, create it
	 * @param string $name
	 * @return object
	 */
	public static function getComponent($name) 
	{
		if (!isset(self::$_componentsList[$name])) {
			self::createComponent($name);
		}
		
		return self::$_componentsList[$name];
	}
	
	/**
	 * Create component
	 * @param string $name
	 */
	private static function createComponent($name)
	{
		if (!isset(self::$_components[$name])) {
			throw new RException('Undefined component: ' . $name);
		}
		
		$reflectionClass = new ReflectionClass($name);
		self::$_componentsList[$name] = empty(self::$_components[$name]) ?
											$reflectionClass->newInstance() : 
											$reflectionClass->newInstance(self::$_components[$name]);
	}
	
	/**
	 * Register new component
	 * @param string $name
	 * @param array $params
	 */
	private static function registerComponent($name, array $params = array())
	{
		self::$_components[$name] = $params;
	}
	
	/**
	 * Magic method for catch static methods
	 * @param type $name
	 * @param type $arguments
	 * @return type
	 */
	public static function __callStatic($name, $arguments) 
	{
		return self::getComponent(ucfirst($name));
	}
	
	public static function getBaseUrl()
	{
		return isset(self::$_config['app']['baseUrl']) ? self::$_config['app']['baseUrl'] : '/';
	}
}