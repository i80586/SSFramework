<?php

/**
 * Request class
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 26 December 2013
 */
class SSRequest
{
	
	/**
	 * Is ajax request
	 * @return boolean
	 */
	public function isAjax()
	{
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) && ('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']));
	}
	
	/**
	 * Get params from GET
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getQuery($name, $defaultValue = null)
	{
		return isset($_GET[$name]) ? $_GET[$name] : $defaultValue;
	}
	
	/**
	 * Get params from POST
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getPost($name, $defaultValue = null)
	{
		return isset($_POST[$name]) ? $_POST[$name] : $defaultValue;
	}
	
}