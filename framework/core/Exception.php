<?php

namespace SS\framework\core;

/**
 * Exception class file
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
     * @param string $message
     * @param array $params
     */
    public function __construct($message, array $params = [])
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
    public static function catchError($errno, $errstr, $errfile = '', $errline = 0, array $errcontext = [])
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
     * @param integer $errorType
     * @param string $message
     * @param string $errorFile
     * @param integer $errorLine
     */
    protected static function trace($errorType, $message, $errorFile = null, $errorLine = null)
    {
        $header = $errorType === self::ERR_EXCEPTION ? 'SS\Exception' : 'PHP Error';
        include FRAMEWORK_DIR . 'views/error.php';
        Application::stop();
    }

}
