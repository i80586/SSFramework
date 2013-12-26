<?php

/**
 * RException class file
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
class SSException extends Exception
{
	/**
	 * Class construction
	 * Throw error
	 * @param string $message
	 * @param integer $code
	 * @param mixed $previous
	 */
	public function __construct($message, $code = null, $previous = null)
	{
		die('Error: ' . $message);
		parent::__construct($message);
	}
	
}
