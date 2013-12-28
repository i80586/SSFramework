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
	 * Class construction
	 * Throw error
	 * @param string $message
	 * @param integer $code
	 * @param mixed $previous
	 */
	public function __construct($message = null, $code = null, $previous = null)
	{
		$errorMessage = (null === $message) ? error_get_last() : $message;
	}
	
	public static function catchError()
	{
		var_dump(error_get_last());
	}
	
	
}
