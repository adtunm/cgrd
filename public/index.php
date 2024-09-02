<?php

use App\Boot;
use App\Routing\Router;

require __DIR__ . '/../vendor/autoload.php';

ob_start();
try {
    Boot::initialize();
    require __DIR__ . '/../routing/web.php';
    Router::handleRequest();
    ob_end_flush();
} catch (Exception $exception) {
    ob_end_clean();
    require __DIR__ . '/error.php';
}
