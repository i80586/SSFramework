<?php

define('BASE_PATH', __DIR__);
define('FRAMEWORK_DIR', BASE_PATH . '/framework/');

require FRAMEWORK_DIR . 'core/App.php';

// start application
(new \framework\core\App(require BASE_PATH . '/config/default.php'))->start();
