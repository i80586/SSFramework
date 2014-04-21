<?php

namespace SS\framework\core;

use \SS\framework\core\Exception;

/**
 * Core class of Application
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
class Application
{

	/**
	 * Class paths to autoloading
	 * @var array 
	 */
	public static $classmap = [];

	/**
	 * Application config
	 * @var array 
	 */
	private static $_config;

	/**
	 * Database handler
	 * @var SS\Database 
	 */
	private static $_dbHandler = null;

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
		if (isset(self::$_config['app']['timezone'])) {
			date_default_timezone_set(self::$_config['app']['timezone']);
		}
		spl_autoload_register('self::loadClasses');
		set_error_handler('\SS\framework\core\Exception::catchError', E_ALL);
		set_exception_handler('\SS\framework\core\Exception::catchException');
	}

	/**
	 * Load classes
	 * @param string $class
	 */
	private static function loadClasses($class)
	{
		self::loadClass($class);
	}

	/**
	 * Load class
	 * @param string $className
	 */
	private static function loadClass($className)
	{
		$className = ltrim($className, 'SS\\');
		$classPath = str_replace('\\', '/', $className);

		if (isset(self::$classmap[$className])) {
			include self::$classmap[$className];
		} elseif (is_file($requiredFile = BASE_PATH . DS . $classPath . '.php')) {
			include $requiredFile;
		}
	}

	/**
	 * Start web application
	 * @throws SS\framework\core\Exception
	 */
	public function start()
	{
		list($controller, $action) = self::urls()->parse($_GET);

		$controllerClass = 'SS\application\controllers\\' . ucfirst($controller) . 'Controller';
		$actionName = 'on' . ucfirst($action);

		self::loadClass($controllerClass);

		try {
			$reflectionMethod = new \ReflectionMethod($controllerClass, $actionName);
		} catch (\ReflectionException $e) {
			throw new Exception('Action <b>:a</b> not found in <b>:c</b>.', array(':a' => $action, ':c' => $controllerClass));
		}

		$reflactionClass = $reflectionMethod->getDeclaringClass();

		if (!$reflactionClass->isSubclassOf('SS\application\components\Controller')) {
			throw new Exception("Controller <b>:c</b> must be a child class of SS\application\components\Controller", array(':c' => $controllerClass));
		}

		if (!$reflactionClass->hasMethod($actionName)) {
			throw new Exception("Action <b>:a</b> not found in <b>%c</b>", array(':a' => $action, ':c' => $controllerClass));
		}

		$reflectionMethod->invoke($reflactionClass->newInstance());
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

	/**
	 * Magic method for catch static methods
	 * @param string $name
	 * @param array $arguments
	 * @return object
	 */
	public static function __callStatic($name, array $arguments = [])
	{
		return Components::getComponent($name, $arguments);
	}

	/**
	 * Get base url
	 * @return string
	 */
	public static function getBaseUrl()
	{
		return isset(self::$_config['app']['baseUrl']) ? self::$_config['app']['baseUrl'] : '/';
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
		\SS\framework\components\Dumper::dump($data);

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
