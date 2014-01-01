<?php

namespace SS;

/**
 * Request class
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 26 December 2013
 */
class Request
{

	/**
	 * Request uri
	 * @var string 
	 */
	private $_requestUri = null;

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

	/**
	 * Returns the named GET or POST parameter value.
	 * @param type $name
	 * @param mixed $defaultValue
	 * @return type
	 */
	public function getParam($name, $defaultValue = null)
	{
		return isset($_GET[$name]) ? $_GET[$name] : (isset($_POST[$name]) ? $_POST[$name] : $defaultValue);
	}

	/**
	 * Returns the request URI portion for the currently requested URL.
	 * This refers to the portion that is after the {@link hostInfo host info} part.
	 * It includes the {@link queryString query string} part if any.
	 * The implementation of this method referenced Zend_Controller_Request_Http in Zend Framework.
	 * @return string the request URI portion for the currently requested URL.
	 * @throws CException if the request URI cannot be determined due to improper server configuration
	 */
	public function getRequestUri()
	{
		if (null === $this->_requestUri) {
			if (isset($_SERVER['HTTP_X_REWRITE_URL'])) { // IIS
				$this->_requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
			} elseif (isset($_SERVER['REQUEST_URI'])) {
				$this->_requestUri = $_SERVER['REQUEST_URI'];
				
				if (!empty($_SERVER['HTTP_HOST'])) {
					if (strpos($this->_requestUri, $_SERVER['HTTP_HOST']) !== false) {
						$this->_requestUri = preg_replace('/^\w+:\/\/[^\/]+/', '', $this->_requestUri);
					}
				} else {
					$this->_requestUri = preg_replace('/^(http|https):\/\/[^\/]+/i', '', $this->_requestUri);
				}
			} elseif (isset($_SERVER['ORIG_PATH_INFO'])) {  // IIS 5.0 CGI
				$this->_requestUri = $_SERVER['ORIG_PATH_INFO'];
				
				if (!empty($_SERVER['QUERY_STRING'])) {
					$this->_requestUri.='?' . $_SERVER['QUERY_STRING'];
				}
			} else {
				throw new Exception('SS\Request is unable to determine the request URI.');
			}
		}

		return $this->_requestUri;
	}

}
