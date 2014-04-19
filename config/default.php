<?php

/**
 * Application config array
 */
return [
	'app' => [
		'baseUrl' => '/',
		'staticUrl' => '/static',
		'defaultController' => 'main',
		'timezone' => 'Asia/Baku'
	],
	
	/**
	 * Clear comments if you'll use database
	'db' => [
		'dsn' => 'mysql:host=localhost;dbname=test;',
		'username' => 'root',
		'password' => '',
		'encoding' => 'utf8'
	],
	 * */
	
	/* don't clear. but you can insert new directory */
	'defaultAutoLoadPaths' => [
		'/application/controllers',
		'/application/components',
		'/application/models',
	]
];
