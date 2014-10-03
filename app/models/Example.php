<?php

namespace app\models;

/**
 * Test model
 */
class Example extends \framework\core\Model
{
	/**
	 * Get example content
     * 
	 * @return string
	 */
	public function getContent()
	{
		return \framework\core\App::getName() . ' is ready!';
	}
	
}