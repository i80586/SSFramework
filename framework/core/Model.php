<?php

namespace framework\core;

/**
 * Model class 
 * Base class of models
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date January 1, 2013
 */
abstract class Model
{

    /**
     * List of attributes
     * 
     * @property array 
     */
    public $attributes = [];

    /**
     * Get model instance
     * 
     * @return framework\core\Model
     */
    public static function model()
    {
        static $model = null;
        if (empty($model)) {
            $model = new static;
        }
        return $model;
    }

    /**
     * Get attribute
     * 
     * @param string $name
     */
    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    /**
     * Get attribute value
     * 
     * @param string $name
     * @return void|mixed
     * @throws \framework\core\Exception
     */
    public function getAttribute($name)
    {
        if (!array_key_exists($name, $this->attributes)) {
            throw new Exception('Attribute <b>:a</b> not found in <b>:m</b> model', [':a' => $name, ':m' => get_class($this)]);
        }
        return $this->attributes[$name];
    }

    /**
     * Set attributes
     * 
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

}
