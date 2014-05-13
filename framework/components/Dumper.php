<?php

namespace framework\components;

/**
 * framework\components\Dumper class file
 * Dump data as string
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 7 January 2013
 */
class Dumper
{

    /**
     * @var array 
     */
    private static $_objects = [];

    /**
     * @var string
     */
    private static $_output;

    /**
     * Dump data
     * @param mixed $data
     */
    public static function dump($data)
    {
        self::dumpInternal($data, 0);
        $result = highlight_string("<?php\n" . self::$_output, true);
        $output = preg_replace('/&lt;\\?php<br \\/>/', '', $result, 1);
        echo $output;
    }

    /**
     * Release dump
     * @param mixed $var
     * @param integer $level
     */
    private static function dumpInternal($var, $level)
    {
        switch (gettype($var)) {
            case 'boolean':
                self::$_output .= $var ? 'true' : 'false';
                break;
            case 'integer':
                self::$_output .= "$var";
                break;
            case 'double':
                self::$_output .= "$var";
                break;
            case 'string':
                self::$_output .= "'" . addslashes($var) . "'";
                break;
            case 'resource':
                self::$_output .= '{resource}';
                break;
            case 'NULL':
                self::$_output .= "null";
                break;
            case 'unknown type':
                self::$_output .= '{unknown}';
                break;
            case 'array':
                self::$_output .= 'array(...)';

                if (empty($var)) {
                    self::$_output .= 'array()';
                } else {
                    $keys = array_keys($var);
                    $spaces = str_repeat(' ', $level * 4);
                    self::$_output .= "array\n" . $spaces . '(';

                    foreach ($keys as $key) {
                        self::$_output .= "\n" . $spaces . '    ';
                        self::dumpInternal($key, 0);
                        self::$_output .= ' => ';
                        self::dumpInternal($var[$key], $level + 1);
                    }

                    self::$_output .= "\n" . $spaces . ')';
                }
                break;
            case 'object':
                if (($id = array_search($var, self::$_objects, true)) !== false) {
                    self::$_output .= get_class($var) . '#' . ($id + 1) . '(...)';
                }

                self::$_output .= get_class($var) . '(...)';
                break;
        }
    }

}
