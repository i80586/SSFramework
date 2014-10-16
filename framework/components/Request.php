<?php

namespace framework\components;

/**
 * Request class
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 26 December 2013
 */
class Request extends \framework\core\BaseComponent
{

    /**
     * Request uri
     * 
     * @var string 
     */
    protected $_requestUri = null;
    
    /**
     * Base url
     * 
     * @var string 
     */
    protected $_baseUrl = null;
    
    /**
     * Script url
     * 
     * @var string 
     */
    protected $_scriptUrl = null;
    
    /**
     * Host info
     * 
     * @var string 
     */
    protected $_hostInfo = null;

    /**
     * Is ajax request
     * 
     * @return boolean
     */
    public function isAjax()
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) && ('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']));
    }

    /**
     * Get params from GET
     * 
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
     * 
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
     * 
     * @param string $name
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getParam($name, $defaultValue = null)
    {
        return isset($_GET[$name]) ? $_GET[$name] : (isset($_POST[$name]) ? $_POST[$name] : $defaultValue);
    }

    /**
     * Get request url
     * @return string
     * @throws \framework\core\Exception
     */
    public function getRequestUri()
    {
        if (null === $this->_requestUri) {
            if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
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
            } elseif (isset($_SERVER['ORIG_PATH_INFO'])) {
                $this->_requestUri = $_SERVER['ORIG_PATH_INFO'];

                if (!empty($_SERVER['QUERY_STRING'])) {
                    $this->_requestUri.='?' . $_SERVER['QUERY_STRING'];
                }
            } else {
                throw new \framework\core\Exception('framwork\components\Request is unable to determine the request URI.');
            }
        }

        return $this->_requestUri;
    }
    
    /**
	 * Return if the request is sent via secure channel (https).
     * 
	 * @return boolean if the request is sent via secure channel (https)
	 */
	public function getIsSecureConnection()
    {
        return isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'on') === 0 || $_SERVER['HTTPS'] == 1) ||
               isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0;
    }

    /**
	 * Returns the schema and host part of the application URL.
	 * The returned URL does not have an ending slash.
	 * By default this is determined based on the user request information.
	 * You may explicitly specify it by setting the {@link setHostInfo hostInfo} property.
     * 
	 * @param string $schema schema to use (e.g. http, https). If empty, the schema used for the current request will be used.
	 * @return string schema and hostname part (with port number if needed) of the request URL (e.g. http://www.yiiframework.com)
	 */
	public function getHostInfo($schema = '')
    {
        if ($this->_hostInfo === null) {
            if ($secure = $this->getIsSecureConnection()) {
                $http = 'https';
            } else {
                $http = 'http';
            }
            if (isset($_SERVER['HTTP_HOST'])) {
                $this->_hostInfo = $http . '://' . $_SERVER['HTTP_HOST'];
            } else {
                $this->_hostInfo = $http . '://' . $_SERVER['SERVER_NAME'];
                $port = $secure ? $this->getSecurePort() : $this->getPort();

                if (($port !== 80 && !$secure) || ($port !== 443 && $secure)) {
                    $this->_hostInfo .= ':' . $port;
                }
            }
        }
        if ($schema !== '') {
            $secure = $this->getIsSecureConnection();
            if ($secure && $schema === 'https' || !$secure && $schema === 'http') {
                return $this->_hostInfo;
            }

            $port = $schema === 'https' ? $this->getSecurePort() : $this->getPort();
            if ($port !== 80 && $schema === 'http' || $port !== 443 && $schema === 'https') {
                $port = ':' . $port;
            } else {
                $port = '';
            }

            $pos = strpos($this->_hostInfo, ':');
            return $schema . substr($this->_hostInfo, $pos, strcspn($this->_hostInfo, ':', $pos + 1) + 1) . $port;
        } else {
            return $this->_hostInfo;
        }
    }

    /**
	 * Returns the relative URL of the entry script.
	 * The implementation of this method referenced Zend_Controller_Request_Http in Zend Framework.
     * 
	 * @throws Exception when it is unable to determine the entry script URL.
	 * @return string the relative URL of the entry script.
	 */
	public function getScriptUrl()
    {
        if (null === $this->_scriptUrl) {
            $scriptName = basename($_SERVER['SCRIPT_FILENAME']);
            if (basename($_SERVER['SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['SCRIPT_NAME'];
            } elseif (basename($_SERVER['PHP_SELF']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['PHP_SELF'];
            } elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['ORIG_SCRIPT_NAME'];
            } elseif (($pos = strpos($_SERVER['PHP_SELF'], '/' . $scriptName)) !== false) {
                $this->_scriptUrl = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $scriptName;
            } elseif (isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['DOCUMENT_ROOT']) === 0) {
                $this->_scriptUrl = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));
            } else {
                throw new CException(Yii::t('yii', 'CHttpRequest is unable to determine the entry script URL.'));
            }
        }
        return $this->_scriptUrl;
    }
    
    /**
     * Set base url
     * 
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->_baseUrl = $baseUrl;
    }
    
    /**
	 * Returns the relative URL for the application.
	 * This is similar to {@link getScriptUrl scriptUrl} except that
	 * it does not have the script file name, and the ending slashes are stripped off.
     * 
	 * @param boolean $absolute whether to return an absolute URL. Defaults to false, meaning returning a relative one.
	 * @return string the relative URL for the application
	 */
	public function getBaseUrl($absolute = false)
    {
        if (null === $this->_baseUrl) {
            $this->_baseUrl = rtrim(dirname($this->getScriptUrl()), '\\/');
        }
        return $absolute ? $this->getHostInfo() . $this->_baseUrl : $this->_baseUrl;
    }

}
