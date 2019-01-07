<?php

namespace Piface\Router;

use Psr\Http\Message\ServerRequestInterface;

class Router implements RouterInterface
{
    /**
     * @var RouterContainer
     */
    private $routes;

    public function __construct()
    {
        $this->routes = new RouterContainer();
    }

    /**
     * Register a GET route.
     *
     * @param callable|string $action
     */
    public function get(string $uri, string $name, $action): Route
    {
        return $this->routes->addRoute($this->createRoute('GET', $uri, $name, $action));
    }

    /**
     * Return an array of all routes.
     *
     * @return Route[]
     */
    public function getAllRoutes(): array
    {
        return $this->routes->getAllRoutes();
    }

    /**
     * Compare the given request with routes in the routerContainer.
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        // At first we need ton determine which type of method is called
        $method = $request->getMethod();

        foreach ($this->routes->getRoutesForSpecificMethod($method) as $route) {
            if ($this->routes->match($request, $route)) {
                return $route;
            }
        }

        return null;
    }

    /**
     * Create a new route.
     *
     * @param callable|string $action
     */
    private function createRoute(string $method, string $uri, string $name, $action): Route
    {
        if (\is_string($action)) {
            $action = $this->convertToControllerAction($action);
        }

        return new Route($method, $uri, $name, $action);
    }

    /**
     * Convert a string action like "IndexController@index" to a namespace.
     */
    private function convertToControllerAction(string $action): string
    {
        $action = 'App\\Controller\\'.$action;

        return $action;
    }
}
