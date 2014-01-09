<?php

namespace SS;

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
	 * @var string
	 */
	protected $layout;
	
	/**
	 * Title of page
	 * @var string 
	 */
	protected $pageTitle;

	/**
	 * Render view file with layout
	 * @param string $view
	 * @param array $data
	 */
	protected function render($view, $data = array())
	{
		$layoutFile = BASE_PATH . '/application/views/' . $this->layout . '.php';
		$content = $this->processView($view, $data);
		
		if (is_file($layoutFile)) {
			include $layoutFile;
		}
	}
	
	/**
	 * Render view file without layout
	 * @param string $view
	 * @param array $data
	 */
	protected function renderPartial($view, $data = array())
	{
		echo $this->processView($view, $data);
	}

	/**
	 * Process view file
	 * @param string $view
	 * @param array $data
	 * @return string
	 * @throws RException
	 */
	private function processView($view, $data = array())
	{
		$controllerName = lcfirst(str_replace('Controller', '', get_class($this)));
		$viewFile = BASE_PATH . '/application/views/' . $controllerName . '/' . $view . '.php';
		
		if (is_file($viewFile)) {
			extract($data);
			
			ob_start();
			include $viewFile;
			return ob_get_clean();
		} else {
			throw new Exception("View <b>:v</b> not found at <i>%f</i>", array(':v' => $view, ':f' => $viewFile));
		}
	}
	
	/**
	 * Redirect to url/route
	 * @param type $route
	 * @param array $params
	 * @param type $redirectCode
	 * @return type
	 */
	protected function redirect($route, array $params = array(), $redirectCode = 301)
	{
		if (('/' == substr($route, 0, 1)) || (false !== strpos('http://', $route)) || (false !== strpos('https://', $route))) {
			$redirectUrl = $route;
		} else {
			$redirectUrl = Application::getBaseUrl() . '?r=' . preg_replace('/[^a-zA-Z\/]/', '', $route);

			$redirectUrl .= array_map(function($param, $value) {
				return sprintf("&%s=%s", $param, $value);
			}, array_keys($params), array_values($params))[0];
		}
		
		header("Location: " . $redirectUrl, true, $redirectCode);
	}
	
	/**
	 * Get page title
	 * @return string
	 */
	public function getPageTitle()
	{
		return $this->pageTitle;
	}
	
	/**
	 * Set current page title
	 * @param string $pageTitle
	 */
	public function setPageTitle($pageTitle)
	{
		$this->pageTitle = $pageTitle;
	}
	
}
