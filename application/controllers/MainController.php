<?php

use SS\Application;

/**
 * MainController file
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
class MainController extends Controller
{
	/**
	 * Index action
	 */
	public function onIndex()
	{
		$m = new Post();
		$m->applyAttributes(array('value' => 'asdd'));
		echo $m->value;
		
		echo Application::getAppName() . ' is ready!';
	}
	
}