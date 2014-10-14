<?php

namespace framework\components;

/**
 * framework\components\IFrontController interface
 * Interface of front controllers
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 26 June 2012
 */
interface IFrontController
{
    /**
     * Set module
     * 
     * @param string $module
     */
    public function setModule($module);
    
	/**
	 * Set controller
	 * 
	 * @param string $controller
	 */
	public function setController($controller);
	
	/**
	 * Set action
	 * 
	 * @param string $action
	 */
	public function setAction($action);

	/**
	 * Set params
	 * 
	 * @param array $params
	 */
	public function setParams(array $params);

	/**
	 * Run action
	 */
	public function run();
}
