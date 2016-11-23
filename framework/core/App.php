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
    public static $get;
    
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
    private $_dbHandler;
    
    /**
     * Front controller handler
     * 
     * @var \framework\components\FrontController  
     */
    private $_fcHandler;
    
    /**
     * Module
     * 
     * @var array 
     */
    private $_module;
    
    /**
     * Controller
     * 
     * @var string 
     */
    private $_controller;
    
    /**
     * Action
     * 
     * @var string 
     */
    private $_action;
    
    /**
     * Request component handler
     * 
     * @var framework\components\Request 
     */
    public $request;
    
    /**
     * Base url
     * 
     * @var string 
     */
    public $baseUrl;

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
        self::$get = new static($config);
        
        // framework initialization
        self::$get->init();
        
        // set configration
        self::$get->setParams();
        
        // set front controller handler
        self::$get->_fcHandler = new \framework\components\FrontController(
                // get query options
                self::$get->router()->parseQuery($_GET)
            );
        
        // run action
        self::$get->_fcHandler->run();
    }
    
    /**
     * Set params
     */
    protected function setParams()
    {
        self::$get->request = self::$get->request();
        if (!is_null(self::$get->config('baseUrl'))) {
            self::$get->request->setBaseUrl(self::$get->config('baseUrl'));
        }
        self::$get->baseUrl = self::$get->request->getBaseUrl();
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
     * Get base url
     * 
     * @return string
     */
    public function baseUrl()
    {
        return self::$get->baseUrl;
    }

    /**
     * Get current configuration
     * 
     * @param string|null
     * @return mixed
     */
    public function config($param = null)
    {
        if (empty($param)) {
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
