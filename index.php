<?php

define('BASE_PATH', __DIR__);

$config = require BASE_PATH . '/config/default.php';
require BASE_PATH . '/framework/core/Application.php';

$app = new \SS\Application($config);
\SS\Application::$classmap = include BASE_PATH . '/config/classMap.php';
$app->start();