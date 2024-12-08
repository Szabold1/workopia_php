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
    public function route($uri)
    {
        // get the request method
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // split the URI into parts by slashes
        $uriParts = explode('/', trim($uri, '/'));

        foreach ($this->routes as $route) {
            // split the route URI into parts by slashes
            $routeParts = explode('/', trim($route['uri'], '/'));

            // check if method and number of parts match
            if (
                strtoupper($route['method']) === strtoupper($requestMethod)
                && count($routeParts) === count($uriParts)
            ) {
                $params = [];
                $match = true;
                $regExp = '/\{(.+?)\}/'; // any word between curly braces

                for ($i = 0; $i < count($routeParts); $i++) {
                    // if the parts don't match and the part is not a parameter
                    if ($routeParts[$i] !== $uriParts[$i] && !preg_match($regExp, $routeParts[$i])) {
                        $match = false;
                        break;
                    }

                    // if the part is a parameter, add it to the params array
                    if (preg_match($regExp, $routeParts[$i], $matches)) {
                        $match = true;
                        $params[$matches[1]] = $uriParts[$i]; // e.g. ['id' => 1]
                    }
                }

                if ($match) {
                    // extract the controller and $method (e.g. 'HomeController@index')
                    $controller = "App\\Controllers\\" . $route['controller'];
                    $controllerMethod = $route['controllerMethod'];

                    // instantiate the controller and call the method
                    $contr = new $controller(); // e.g. new HomeController()
                    $contr->$controllerMethod($params); // e.g. HomeController->index() or ListingController->show(['id' => 1])

                    return;
                }
            }
        }

        ErrorController::notFound();
    }
};
