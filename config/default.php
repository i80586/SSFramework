<?php

/**
 * Application config array
 */
return array(
	'app' => array(
		'baseUrl' => '/',
		'staticUrl' => '/static',
		'defaultController' => 'main',
		'timezone' => 'Asia/Baku'
	),
	
	// don't clear. you can insert new directory
	'defaultAutoLoadPaths' => array(
		FRAMEWORK_DIR . 'core',
		FRAMEWORK_DIR . 'components',
		'/application/controllers',
		'/application/components',
		'/application/models',
	)
);
