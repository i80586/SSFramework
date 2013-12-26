<?php

/**
 * MainController file
 * 
 * @author Rasim Ashurov <rasim.ashurov@gmail.com>
 * @date 25 December 2013
 */
class MainController extends SSController
{
	/**
	 * Index action
	 */
	public function onIndex()
	{
		echo SSApplication::request()->getQuery('');
	}
	
}