<?php

define('BASE_PATH', __DIR__);
define('SS_DIR', BASE_PATH . '/framework/core');

require SS_DIR . '/SSApplication.php';

$app = new SSApplication(require BASE_PATH . '/config/default.php');
SSApplication::$classmap = require BASE_PATH . '/config/classMap.php';
$app->start();