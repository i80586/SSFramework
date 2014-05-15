<?php

/**
 * Application config array
 */
return [
	// base application configuration
	'app' => [
		'baseUrl' => '/',
		'staticUrl' => '/static',
		'defaultController' => 'main',
	],
	
	// user components
	'components' => [
		'test' => [
			'path' => '\app\components\Test',
		]
	],
	
	 /* Clear comments if you'll use database
	'db' => [
		'dsn' => 'mysql:host=localhost;dbname=test;',
		'username' => 'root',
		'password' => '',
		'encoding' => 'utf8'
	],
	*/
	
];
