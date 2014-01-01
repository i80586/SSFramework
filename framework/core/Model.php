<?php

namespace SS;

/**
 * Class Model
 * Core class of all models
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 1 January 2013
 */
abstract class Model
{
	/**
	 * Attributes of model
	 * @var type 
	 */
	public $attributes = [];
	
	/**
	 * 
	 * @param type $name
	 */
	public function __get($name)
	{
		if (!isset($this->attributes[$name])) {
			// throw exception
		}
		
		return $this->attributes[$name];
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