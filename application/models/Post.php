<?php

namespace application\models;

/**
 * Test model
 */
class Post extends \framework\core\Model
{
	/**
	 * Get example content
	 * @return string
	 */
	public function getContent()
	{
		return \framework\core\Application::getAppName() . ' is ready!';
	}
	
}