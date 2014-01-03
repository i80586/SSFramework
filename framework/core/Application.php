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
		date_default_timezone_set(isset(self::$_config['app']['timezone']) ? self::$_config['app']['timezone'] : 'date_default_timezone_set()');
		spl_autoload_register('self::loadClasses');
		set_error_handler('\SS\Exception::catchError', E_ALL);
		set_exception_handler('\SS\Exception::catchException');
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
			throw new Exception("Controller <b>:c</b> not found", array(':c' => $controllerClass));
		}
		
		try {
			$reflectionMethod = new \ReflectionMethod($controllerClass, $actionName);
		} catch (\ReflectionException $e) {
			throw new Exception('Action <b>:a</b> not found in <b>:c</b>.', 
					array(':a' => $action, ':c' => $controllerClass));
		}
		
		$reflactionClass = $reflectionMethod->getDeclaringClass();
		
		if (!$reflactionClass->isSubclassOf('\Controller')) {
			throw new Exception("Controller <b>:c</b> must be a child class of \Controller", array(':c' => $controllerClass));
		}
		
		if (!$reflactionClass->hasMethod($actionName)) {
			throw new Exception("Action <b>:a</b> not found in <b>%c</b>", array(':a' => $action, ':c' => $controllerClass)); 
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
		return Components::getComponent(ucfirst($name), $arguments);
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
	
	/**
	 * Get current configuration
	 * @return array
	 */
	public static function getConfig()
	{
		return self::$_config;
	}
	
	/**
	 * Structured data dumper
	 * @param mixed $data
	 * @param boolean $terminate
	 */
	public static function dump($data, $terminate = true)
	{
		$isBrowser = isset($_SERVER['HTTP_USER_AGENT']);
		
		if ($isBrowser) {
			echo '<pre>';
		}
		
		print_r($data);
		
		if ($isBrowser) {
			echo '</pre>';
		}
		
		if ($terminate) {
			self::stop();
		}
	}
	
	/**
	 * Get application name
	 * @return string
	 */
	public static function getAppName()
	{
		return 'SSFramework ' . self::getVersion();
	}
	
	/**
	 * Get version of the framework
	 * @return string
	 */
	public static function getVersion()
	{
		return '0.1';
	}
	
	/**
	 * Stop application
	 */
	public static function stop()
	{
		exit;
	}
}