<?php
require '../helpers.php';
require basePath('vendor/autoload.php');
require basePath('Database.php');

// instantiate the router
require basePath('Router.php');
$router = new Router();

// require the routes
require basePath('routes.php');

// get request URI and method, and route to the appropriate controller
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$router->route($method, $uri);
