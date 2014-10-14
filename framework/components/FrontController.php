<?php

namespace framework\components;

use framework\core\Exception;
use framework\core\App;

/**
 * framework\components\FrontController class
 * Interface of front controllers
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 26 June 2013
 */
class FrontController implements IFrontController
{
	/**
	 * Default options constants
	 */
	const DEFAULT_CONTROLLER = 'main';
	const DEFAULT_ACTION = 'index';
	
    /**
     * Module name
     * 
     * @var string 
     */
    protected $_module = null;
    /**
	 * Controller class name
	 * 
	 * @var string 
	 */
	protected $_controller = null;
	
	/**
	 * Action method name
	 * 
	 * @var string 
	 */
	protected $_action = null;
	
	/**
	 * Params
	 * 
	 * @var array 
	 */
	protected $_params = [];
	
	/**
	 * Reflection handler
	 * 
	 * @var mixed 
	 */
	private $_reflection = null;
	
	/**
	 * Class constructor
	 * 
	 * @param array $options
	 */
	public function __construct(array $options = [])
	{
		$this->setModule($this->getModule($options))
             ->setController($this->getController($options))
			 ->setAction($this->getAction($options))
			 ->setParams($this->getParams($options));
	}
	
    /**
     * Get module
     * 
     * @param array $options
     * @return mixed
     */
    public function getModule(array $options = null)
    {
        if (null === $options) {
            return $this->_module;
        }
        return (isset($options['module'])) ? $options['module'] : null;
    }
    
	/**
	 * Get controller
	 * 
	 * @param array $options
	 * @return string
	 */
	public function getController(array $options = null)
	{
        if (null === $options) {
            return $this->_controller;
        }
        
		$controller = isset($options['controller']) ? $options['controller'] : null;
		if (empty($controller) && is_null($controller = App::$get->config('defaultController'))) {
            $controller = self::DEFAULT_CONTROLLER;
		}
		
		return $controller;
	}
	
	/**
	 * Get action
	 * 
	 * @param array $options
	 * @return array
	 */
	public function getAction(array $options = null)
	{
        if (null === $options) {
            return $this->_action;
        }
        
		return isset($options['action']) ? $options['action'] : self::DEFAULT_ACTION;
	}
	
	/**
	 * Get params
	 * 
	 * @param array $options
	 * @return array
	 */
	private function getParams(array $options)
	{
		return isset($options['params']) ? $options['params'] : [];
	}
    
    /**
     * Set module
     * 
     * @param mixed $module
     * @return \framework\components\FrontController
     * @throws Exception
     */
    public function setModule($module)
    {
        if (!is_null($module)) {
            $moduleClass = 'app\modules\\' . $module . '\\' . ucfirst($module) . 'Module';

            // check for module class
            try {
                $this->_reflection = new \ReflectionClass($moduleClass);
            } catch (\ReflectionException $e) {
                throw new Exception('Module <b>:m</b> not found.', [':m' => $moduleClass]);
            }

            $this->_module = [
                'name' => $module,
                'class' => $moduleClass
            ];
        }
        
		return $this;
    }
	
	/**
	 * Set controller class name
	 * 
	 * @param string $controller
	 * @return \framework\components\FrontController
	 * @throws Exception
	 */
	public function setController($controller)
	{
        if (!is_null($this->_module)) {
            $controllerClass = 'app\modules\\' . $this->_module['name'] . '\controllers\\' . ucfirst($controller) . 'Controller';
        } else {
            $controllerClass = 'app\controllers\\' . ucfirst($controller) . 'Controller';
        }
        		
		// check for controller class
		try {
			$this->_reflection = new \ReflectionClass($controllerClass);
		} catch (\ReflectionException $e) {
			throw new Exception('Controller <b>:c</b> not found.', [':c' => $controllerClass]);
		}
		
		$this->_controller = $controllerClass;
		return $this;
	}
	
	/**
	 * Set action method name
	 * 
	 * @param string $action
	 * @return \framework\components\FrontController
	 * @throws Exception
	 */
	public function setAction($action)
	{
		$actionName = 'on' . ucfirst($action);
		
		// check for action method
		try {
			$this->_reflection = $this->_reflection->getMethod($actionName);
		} catch (\ReflectionException $e) {
			throw new Exception("Action <b>:a</b> not found in <b>:c</b>", 
						[':a' => $action, ':c' => $this->_controller]);
		}
		
		$this->_action = $actionName;
		return $this;
	}
	
	/**
	 * Set params
	 * 
	 * @param array $params
	 * @return \framework\components\FrontController
	 */
	public function setParams(array $params)
	{
		$this->_params = $params;
		return $this;
	}
	
	/**
	 * Run action in controller
	 * Method in class
	 * 
	 * @throws Exception
	 */
	public function run()
	{
		// get method parameters
		$methodParameters = $this->_reflection->getParameters();
		
		// check method parameters in query
		$parameters = [];
		foreach ($methodParameters as $param) {
			if (null === ($paramValue = App::$get->request()->getQuery($param->name))) {
				throw new Exception('Parameter <b>:param</b> not found in query', [
                                        ':param' => $param->name
                                    ]);
			}
			$parameters[$param->name] = $paramValue;
		}
		
		// run method with parameters
		$this->_reflection->invokeArgs(new $this->_controller, $parameters);
	}
	
}
