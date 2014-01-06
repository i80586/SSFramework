<?php

define('BASE_PATH', __DIR__);
define('FRAMEWORK_DIR', BASE_PATH . '/framework/');

require FRAMEWORK_DIR . 'core/Application.php';

$app = new \SS\Application(require BASE_PATH . '/config/default.php');
// open comment if you want to include classmap file to project
// \SS\Application::$classmap = require BASE_PATH . '/config/classMap.php';
$app->start();