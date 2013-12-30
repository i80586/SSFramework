<?php

namespace SS;

/**
 * RException class file
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
class Exception extends \Exception
{
	/**
	 * Error types contants
	 */
	const ERR_EXCEPTION = 0x1;
	const ERR_PHPERROR = 0x2;
	
	/**
	 * Class construction
	 * Throw error
	 * @param string $message
	 * @param integer $code
	 * @param mixed $previous
	 */
	public function __construct($message = null, $code = null, $previous = null)
	{
		die($message);
		//self::trace($code, $message, $errorLine, $errorString);
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
		self::trace(self::ERR_PHPERROR, $errfile, $errline, $errstr);
		Application::stop();
	}
	
	/**
	 * Generate error trace
	 * @param integer $errorType
	 * @param string $errorFile
	 * @param string $errorLine
	 * @param string $errorString
	 */
	private static function trace($errorType, $errorFile, $errorLine, $errorString)
	{
		$fileLines = file($errorFile);
		include FRAMEWORK_DIR . 'core/views/error.php';
	}
	
	
}
