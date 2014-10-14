<?php

define('BASE_PATH', __DIR__);
define('FRAMEWORK_DIR', BASE_PATH . '/framework/');

require FRAMEWORK_DIR . 'core/App.php';

// start application
\framework\core\App::start(require BASE_PATH . '/config/default.php');