<?php

namespace Framework;

use App\Controllers\ErrorController;

class Router
{
    private $routes = [];

    /**
     * Register a route (add it to the routes array)
     * @param string $method
     * @param string $uri
     * @param string $action
     * @return void
     */
    private function registerRoute($method, $uri, $action)
    {
        // split the controller and method (e.g. 'HomeController@index')
        [$controller, $controllerMethod] = explode('@', $action);

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod
        ];
    }

    /**
     * Handle a GET request
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function get($uri, $controller)
    {
        $this->registerRoute("GET", $uri, $controller);
    }

    /**
     * Handle a POST request
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function post($uri, $controller)
    {
        $this->registerRoute("POST", $uri, $controller);
    }

    /**
     * Handle a PUT request
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function put($uri, $controller)
    {
        $this->registerRoute("PUT", $uri, $controller);
    }

    /**
     * Handle a DELETE request
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function delete($uri, $controller)
    {
        $this->registerRoute("DELETE", $uri, $controller);
    }

    /**
     * Route the request
     * @param string $uri
     * @param string $method
     * @return void
     */
    public function route($method, $uri)
    {
        foreach ($this->routes as $route) {
            // if the method and URI match (e.g. GET /)
            if ($route['method'] === $method && $route['uri'] === $uri) {
                // extract the controller and $method (e.g. 'HomeController@index')
                $controller = "App\\Controllers\\" . $route['controller'];
                $controllerMethod = $route['controllerMethod'];

                // instantiate the controller and call the method
                $contr = new $controller(); // e.g. new HomeController()
                $contr->$controllerMethod(); // e.g. HomeController->index()

                return;
            }
        }

        ErrorController::notFound();
    }
};
