<?php

namespace Framework;

use App\Controllers\ErrorController;
use Framework\Middleware\Authorize;

class Router
{
    private $routes = [];

    /**
     * Register a route (add it to the routes array)
     * @param string $method
     * @param string $uri
     * @param string $action
     * @param array $middleware
     * @return void
     */
    private function registerRoute($method, $uri, $action, $middleware = [])
    {
        // split the controller and method (e.g. 'HomeController@index')
        [$controller, $controllerMethod] = explode('@', $action);

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod,
            'middleware' => $middleware
        ];
    }

    /**
     * Handle a GET request
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * @return void
     */
    public function get($uri, $controller, $middleware = [])
    {
        $this->registerRoute("GET", $uri, $controller, $middleware);
    }

    /**
     * Handle a POST request
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * @return void
     */
    public function post($uri, $controller, $middleware = [])
    {
        $this->registerRoute("POST", $uri, $controller, $middleware);
    }

    /**
     * Handle a PUT request
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * @return void
     */
    public function put($uri, $controller, $middleware = [])
    {
        $this->registerRoute("PUT", $uri, $controller, $middleware);
    }

    /**
     * Handle a DELETE request
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * @return void
     */
    public function delete($uri, $controller, $middleware = [])
    {
        $this->registerRoute("DELETE", $uri, $controller, $middleware);
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

        // handle PUT and DELETE requests
        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            $requestMethod = strtoupper($_POST['_method']);
        }

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
                    // check if the route has middleware and handle it
                    if (isset($route['middleware'])) {
                        foreach ($route['middleware'] as $middleware) {
                            (new Authorize())->handle($middleware);
                        }
                    }
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
