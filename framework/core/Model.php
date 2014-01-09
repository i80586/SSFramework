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
	 * Get model object.
	 * It will be created if not exists
	 * @return object
	 */
	public static function model()
	{
		static $owner = null;
		
		if (null === $owner) {
			$reflectionClass = new \ReflectionClass(get_called_class());
			$owner = $reflectionClass->newInstance();
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
	 * @param type $attributeName
	 * @throws Exception
	 */
	private function attributeExists($name)
	{
		$attributeExists = isset($this->attributes[$name]);
		
		if (!$attributeExists) {
			throw new Exception('Attribute <b>:a</b> not found in <b>:m</b> model', array(':a' => $name, ':m' => get_class($this)));
		}
		
		return $attributeExists;
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
	 * Apply attributes
	 * @param array $attributes
	 */
	public function applyAttributes(array $attributes)
	{
		$this->attributes = $attributes;
	}

}