<?php
require __DIR__ . '/../vendor/autoload.php';

use Framework\Router;
use Framework\Session;

Session::start();
require '../helpers.php';

// instantiate the router
$router = new Router();

// require the routes
require basePath('routes.php');

// get request URI and method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// route to the appropriate controller
$router->route($uri);
