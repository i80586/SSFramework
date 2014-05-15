<?php
define('BASE_PATH', __DIR__);
define('FRAMEWORK_DIR', BASE_PATH . '/framework/');

require FRAMEWORK_DIR . 'core/Application.php';

// start application
(new \framework\core\Application(require BASE_PATH . '/config/default.php'))->start();