<?php

namespace SS\framework\components;

use \SS\framework\core\Application;

/**
 * Urls class
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 19 April 2014
 */
class Urls
{

    /**
     * Parse route
     * @return array
     */
    public function parse($getParams)
    {
        if (isset($getParams['r'])) {
            $route = preg_replace('/[^a-zA-Z\/]/', '', $getParams['r']);
            return (false === strpos($route, '/')) ? [$route, 'index'] : explode('/', $route);
        }

        return [Application::getConfig()['app']['defaultController'], 'index'];
    }

}