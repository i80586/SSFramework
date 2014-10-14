<?php

namespace framework\core;

/**
 * Core class of Application
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
class App
{
    /**
     * Framework version
     */
    const VERSION = '0.4';

    /**
     * Application handler
     * 
     * @var framework\core\App 
     */
    public static $get = null;
    
    /**
     * Application config
     * 
     * @var array 
     */
    private $_config;

    /**
     * Database handler
     * 
     * @var \framework\core\Database 
     */
    private $_dbHandler = null;
    
    /**
     * Front controller handler
     * 
     * @var \framework\components\FrontController  
     */
    private $_fcHandler = null;
    
    /**
     * Module
     * 
     * @var array 
     */
    private $_module = null;
    /**
     * Controller
     * 
     * @var string 
     */
    private $_controller = null;
    /**
     * Action
     * 
     * @var string 
     */
    private $_action = null;    

    /**
     * Class constructor
     * 
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->_config = $config;
    }

    /**
     * Register namespaces and autoloader
     */
    private function registerNamespaces()
    {
        require FRAMEWORK_DIR . 'core/SplClassLoader.php';
        (new \SplClassLoader('framework', BASE_PATH))->register();
        (new \SplClassLoader('app', BASE_PATH))->register();
    }

    /**
     * Initialize function
     */
    protected function init()
    {
        if (isset(self::$get->_config['app']['timezone'])) {
            date_default_timezone_set(self::$get->_config['app']['timezone']);
        }
        // register namespaces and autoloader
        self::$get->registerNamespaces();
        set_error_handler('\framework\core\Exception::catchError', E_ALL);
        set_exception_handler('\framework\core\Exception::catchException');
    }

    /**
     * Starts web application
     */
    public static function start(array $config)
    {
        self::$get = new self($config);
        
        // framework initialization
        self::$get->init();
        
        // run action
        self::$get->_fcHandler = new \framework\components\FrontController(
            // get query options
            self::$get->router()->parseQuery($_GET)
        );
        self::$get->_fcHandler->run();
    }
    
    /**
     * Get module
     * 
     * @return mixed
     */
    public function module()
    {
        if (null === self::$get->_module) {
            self::$get->_module = self::$get->_fcHandler->getModule();
        }
        return self::$get->_module['name'];
    }
    
    /**
     * Get controller
     * 
     * @return string
     */
    public function controller()
    {
        if (null === self::$get->_controller) {
            self::$get->_controller = self::$get->_fcHandler->getContoller();
        }
        return self::$get->_controller;
    }
    
    /**
     * Get action
     * 
     * @return string
     */
    public function action()
    {
        if (null === self::$get->_action) {
            self::$get->_action = self::$get->_fcHandler->getAction();
        }
        return self::$get->_action;
    }

    /**
     * Returns database handler
     * 
     * @return framework\core\Database
     */
    public function db()
    {
        if (null === self::$get->_dbHandler) {
            self::$get->_dbHandler = new \framework\components\Database();
        }
        return self::$get->_dbHandler;
    }

    /**
     * Magic method for catch static methods
     * 
     * @param string $name
     * @param array $arguments
     * @return object
     */
    public function __call($name, array $arguments = [])
    {
        return Components::getComponent($name, $arguments);
    }
    
    /**
     * Get base url
     * 
     * @return string
     */
    public function baseUrl()
    {
        return isset(self::$get->_config['app']['baseUrl']) ? self::$get->_config['app']['baseUrl'] : '/';
    }

    /**
     * Get current configuration
     * 
     * @return mixed
     */
    public function config($param = null)
    {
        if (null === $param) {
            return self::$get->_config;
        }
        return (isset(self::$get->_config[$param])) ? self::$get->_config[$param] : null;
    }

    /**
     * Structured data dumper
     * 
     * @param mixed $data
     * @param boolean $terminate
     */
    public function dump($data, $terminate = true)
    {
        \framework\components\Dumper::dump($data);

        if ($terminate) {
            self::stop();
        }
    }

    /**
     * Get application name
     * 
     * @return string
     */
    public function name()
    {
        return 'SSFramework ' . self::$get->version();
    }

    /**
     * Get version of the framework
     * 
     * @return string
     */
    public function version()
    {
        return self::VERSION;
    }

    /**
     * Stop application
     */
    public function stop()
    {
        exit(1);
    }

}
