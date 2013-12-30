<?php

namespace SS;

/**
 * Abstract class of controller
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
abstract class Controller
{
	/**
	 * @var string
	 */
	protected $layout = 'layouts/main';

	/**
	 * Render view file with layout
	 * @param string $view
	 * @param array $data
	 */
	protected function render($view, $data = array())
	{
		$layoutFile = BASE_PATH . '/application/views/' . $this->layout . '.php';
		$content = $this->processView($view, $data);
		include $layoutFile;
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
			throw new Exception(sprintf("View <b>%s</b> not found at <i>%s</i>", $view, $viewFile));
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
		if ('/' == substr($route, 0, 1)) {
			$redirectUrl = $route;
		} else {
			$redirectUrl = SSApplication::getBaseUrl() . '?r=' . preg_replace('/[^a-zA-Z\/]/', '', $route);

			$redirectUrl .= array_map(function($param, $value) {
				return sprintf("&%s=%s", $param, $value);
			}, array_keys($params), array_values($params))[0];
		}
		
		header("HTTP/1.1 ". $redirectCode ." Moved Permanently"); 
		header("Location: " . $redirectUrl);
	}
	
}
