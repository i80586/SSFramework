<?php

namespace framework\components;

/**
 * Router class
 * Parse query, url
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 19 April 2014
 */
class Router extends \framework\core\BaseComponent
{
    /**
     * Parse query
	 * 
	 * @param array $params
     * @return array
     */
    public function parseQuery(array $params)
    {
        if (isset($params['r'])) {
            $route = preg_replace('/[^a-zA-Z\/]/', '', $params['r']);
			
			if (false === strpos($route, '/')) {
				return ['controller' => $route];
			}
			
			list($controller, $action) = explode('/', $route);
			return ['controller' => $controller, 'action' => $action];
        }
        return [];
    }

}
