<?php

namespace SS;

/**
 * RException class file
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
class Exception
{
	/**
	 * Error types contants
	 */
	const ERR_EXCEPTION = 0x1;
	const ERR_PHPERROR = 0x2;
	
	/**
	 * Class construction
	 * Generate message
	 * @param type $message
	 * @param array $params
	 */
	public function __construct($message, array $params = array())
	{
		self::catchException(str_replace(array_keys($params), array_values($params), $message));
	}
	
	/**
	 * Catch error
	 * @param integer $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param integer $errline
	 * @param array $errcontext
	 */
	public static function catchError($errno, $errstr, $errfile = '', $errline = 0, $errcontext = array())
	{
		self::trace(self::ERR_PHPERROR, $errstr, $errfile, $errline);
	}
	
	/**
	 * Catch exception
	 * @param string $message
	 */
	public static function catchException($message)
	{
		self::trace(self::ERR_EXCEPTION, $message);
	}
	
	/**
	 * Generate error trace
	 * @param string $header
	 * @param string $message
	 * @param mixed $errorFile
	 */
	protected static function trace($errorType, $message, $errorFile = null, $errorLine = null)
	{
		$header = $errorType === self::ERR_EXCEPTION ? 'SS\Exception' : 'PHP Error';
		include FRAMEWORK_DIR . 'views/error.php';
		Application::stop();
	}


}
