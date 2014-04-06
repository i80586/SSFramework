<?php

namespace SS;

/**
 * Class Model
 * Base class of all models
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 1 January 2013
 */
abstract class Model
{

    /**
     * Attributes array
     * @var array 
     */
    public $attributes = [];

    /**
     * Get model instance.
     * @return object
     */
    public static function model()
    {
        static $owner = null;

        if (null === $owner) {
            $ownerClassName = get_called_class();
            $owner = new $ownerClassName();
        }

        return $owner;
    }

    /**
     * Get attribute
     * @param string $name
     */
    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    /**
     * Check for existing attribute
     * @param string $name
     * @throws Exception
     */
    private function attributeExists($name)
    {
        if (!isset($this->attributes[$name])) {
            throw new Exception('Attribute <b>:a</b> not found in <b>:m</b> model', 
                    [':a' => $name, ':m' => get_class($this)]);
        }
        return true;
    }

    /**
     * Get attribute value
     * @param string $name
     * @return string
     */
    public function getAttribute($name)
    {
        if ($this->attributeExists($name)) {
            return $this->attributes[$name];
        }
    }

    /**
     * Set attributes
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

}
