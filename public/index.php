<?php
require '../helpers.php';
require basePath('vendor/autoload.php');
require basePath('Database.php');

// instantiate the router
require basePath('Router.php');
$router = new Router();

// require the routes
require basePath('routes.php');

// get request URI and method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// route to the appropriate controller
$router->route($method, $uri);
