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
	'defaultAutoLoadPaths' => array(
		FRAMEWORK_DIR . 'core',
		FRAMEWORK_DIR . 'components',
		'/application/controllers',
		'/application/models',
	)
);
