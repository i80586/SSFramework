<?php

namespace framework\core;

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
     * @var array
     */
    protected static $_componentsList = [];

    /**
     * Components list
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
        if (!isset(self::$_componentsList[$className = '\framework\components\\' . ucfirst($name)])) {
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
            throw new \framework\core\Exception('Undefined component: ' . $name);
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
    protected static function registerComponent($name, array $params = [])
    {
        self::$_components[$name] = $params;
    }

}
