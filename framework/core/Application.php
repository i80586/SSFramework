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
		spl_autoload_register('self::loadClasses');
		set_error_handler('\SS\Exception::catchError', 
				E_ALL | 
				E_NOTICE |
				E_COMPILE_ERROR | 
				E_COMPILE_WARNING | 
				E_CORE_ERROR | E_CORE_WARNING | E_DEPRECATED | E_NOTICE | E_PARSE);
		set_exception_handler(array('\SS\Exception', 'catchError'));
	}
	
	/**
	 * Load classes
	 * @param string $class
	 * @return
	 */
	private static function loadClasses($class)
	{
		$className = trim($class, 'SS\\');
				
		if (isset(self::$classmap[$className])) {
			include self::$classmap[$className];
			return;
		}
		
		foreach (self::$_config['defaultAutoLoadPaths'] as $path) {
			$requiredFile = $path . DIRECTORY_SEPARATOR . $className . '.php';
			
			if (false === strpos($path, BASE_PATH)) {
				$requiredFile = BASE_PATH . $requiredFile;
			}
			
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
			throw new SSException(sprintf("Controller <b>%s</b> not found", $controllerClass));
		}
		
		$reflectionMethod = new \ReflectionMethod($controllerClass, $actionName);
		$reflactionClass = $reflectionMethod->getDeclaringClass();
		
		if (!$reflactionClass->isSubclassOf('SS\Controller')) {
			throw new SSException(sprintf("Controller <b>%s</b> must be a child class of SS\Controller", $controllerClass));
		}
		
		if (!$reflactionClass->hasMethod($actionName)) {
			throw new SSException(sprintf("Action <b>%s</b> not found in <b>%s</b>", $action, $controllerClass)); 
		}
		
		$reflectionMethod->invoke($reflactionClass->newInstance());
	}

	/**
	 * Magic method for catch static methods
	 * @param type $name
	 * @param type $arguments
	 * @return type
	 */
	public static function __callStatic($name, $arguments) 
	{
		return \SS\Components::getComponent(ucfirst($name), $arguments);
	}
	
	/**
	 * Get base url
	 * @return type
	 */
	public static function getBaseUrl()
	{
		return isset(self::$_config['app']['baseUrl']) ? self::$_config['app']['baseUrl'] : '/';
	}
	
	/**
	 * Get resources url
	 * @return string
	 */
	public static function getStaticUrl()
	{
		return isset(self::$_config['app']['staticUrl']) ? self::$_config['app']['staticUrl'] : '/static';
	}
}