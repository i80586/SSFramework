<?php

/**
 * MainController file
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
class MainController extends \SS\Controller
{
	/**
	 * Index action
	 */
	public function onIndex()
	{
		echo '<b>' . SS\Application::getAppName() . '</b> is ready!';
	}
	
}