<?php

namespace framework\core;

use \framework\core\App;

/**
 * Abstract class of controller
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
abstract class BaseController
{

    /**
     * Layout for views
     * 
     * @var string
     */
    public $layout;

    /**
     * Title of page
     * 
     * @var string
     */
    public $pageTitle;
    
    /**
     * Media resources url
     * 
     * @var string
     */
    public $mediaUrl = null;

    /**
     * Render view file with layout
     * 
     * @param string $view
     * @param array $data
     */
    protected function render($view, array $data = [])
    {
        $content = $this->processView($view, $data);

        if (is_file($layoutFile = $this->getLayoutFile())) {
            include $layoutFile;
        } else {
            echo $content;
        }
    }
	
	/**
	 * Get layout file path
     * 
	 * @return string
	 */
	protected function getLayoutFile()
	{
		return BASE_PATH . '/app/views/' . $this->layout . '.php';
	}
	
	/**
	 * Get controller name
     * 
	 * @param string $controller
	 * @return string
	 */
	protected function getControllerName($controller)
	{
		preg_match('/^.*\\\\([a-z]+)Controller/i', $controller, $match);
		return isset($match[1]) ? lcfirst($match[1]) : '';
	}

	/**
     * Render view file without layout
     * 
     * @param string $view
     * @param array $data
     */
    protected function renderPartial($view, array $data = [])
    {
        echo $this->processView($view, $data);
    }

    /**
     * Process view file
     * 
     * @param string $view
     * @param array $data
     * @return string
     * @throws \framework\core\Exception
     */
    protected function processView($view, array $data = [])
    {
        $controllerName = lcfirst(str_replace('Controller', '', $this->getControllerName(get_class($this))));
        
        if (!is_null(App::$get->module())) {
            $viewFile = BASE_PATH . '/app/modules/' . App::$get->module() . '/views/' . $controllerName . '/' . $view . '.php';
        } else {
            $viewFile = BASE_PATH . '/app/views/' . $controllerName . '/' . $view . '.php';
        }

        if (is_file($viewFile)) {
            extract($data);

            ob_start();
            include $viewFile;
            return ob_get_clean();
        } else {
            throw new \framework\core\Exception("View <b>:v</b> not found at <i>:f</i>", [':v' => $view, ':f' => $viewFile]);
        }
    }

    /**
     * Redirect to url/route
     * 
     * @param string $route
     * @param array $params
     * @param integer $redirectCode
     */
    protected function redirect($route, array $params = [], $redirectCode = 301)
    {
        if (('/' == substr($route, 0, 1)) || (false !== strpos('http://', $route)) || (false !== strpos('https://', $route))) {
            $redirectUrl = $route;
        } else {
            $redirectUrl = App::$get->baseUrl() . '?r=' . preg_replace('/[^a-zA-Z\/]/', '', $route);

            $redirectUrl .= array_map(function($param, $value) {
                        return sprintf("&%s=%s", $param, $value);
                    }, array_keys($params), array_values($params))[0];
        }

        header("Location: " . $redirectUrl, true, $redirectCode);
    }

    /**
     * Get page title
     * 
     * @return string
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * Set current page title
     * 
     * @param string $title
     */
    public function setPageTitle($title)
    {
        $this->pageTitle = $title;
    }
	
    /**
     * Set media url
     * 
     * @param string $url
     */
    public function setMediaUrl($url)
    {
        $this->mediaUrl = $url;
    }
    
	/**
     * Get media resources url
     * 
     * @return mixed
     */
    public function getMediaUrl()
    {
        if (null === $this->mediaUrl) {
            $this->mediaUrl = App::$get->config('mediaUrl');
        }
        return $this->mediaUrl;
    }

}
