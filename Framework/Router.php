<?php

class Router
{
    private $routes = [];

    /**
     * Register a route (add it to the routes array)
     * @param string $method
     * @param string $uri
     * @param string $controller
     * @return void
     */
    private function registerRoute($method, $uri, $controller)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller
        ];
    }

    /**
     * Load error view and exit
     * @param int $httpCode
     * @return void
     */
    private function error($httpCode)
    {
        http_response_code($httpCode);
        loadView("error/{$httpCode}");
        exit;
    }

    /**
     * Handle a GET request
     * @param mixed $uri
     * @param mixed $controller
     * @return void
     */
    public function get($uri, $controller)
    {
        $this->registerRoute("GET", $uri, $controller);
    }

    /**
     * Handle a POST request
     * @param mixed $uri
     * @param mixed $controller
     * @return void
     */
    public function post($uri, $controller)
    {
        $this->registerRoute("POST", $uri, $controller);
    }

    /**
     * Handle a PUT request
     * @param mixed $uri
     * @param mixed $controller
     * @return void
     */
    public function put($uri, $controller)
    {
        $this->registerRoute("PUT", $uri, $controller);
    }

    /**
     * Handle a DELETE request
     * @param mixed $uri
     * @param mixed $controller
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
            // if the method and URI match, require the controller and return
            if ($route['method'] === $method && $route['uri'] === $uri) {
                require basePath("App/" . $route['controller']);
                return;
            }
        }

        $this->error(404);
    }
};
