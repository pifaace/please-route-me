<?php

namespace Piface\Routing;

use Psr\Http\Message\ServerRequestInterface;

class Router
{
    /**
     * @var Router instance
     */
    private static $_instance;

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
        return $this->routes->addRoute($this->createRoute(['GET'], $uri, $name, $action));
    }

    /**
     * Create a new route.
     *
     * @param callable|string $action
     *
     * @return Route
     */
    private function createRoute(array $method, string $uri, string $name, $action): Route
    {
        if (\is_string($action)) {
            $action = $this->convertToControllerAction($action);
        }

        return new Route($method, $uri, $name, $action);
    }

    /**
     * Convert a string action like "IndexController@index" to a namespace
     */
    private function convertToControllerAction(string $action): string
    {
        $action = 'App\\Controller\\'.$action;

        return $action;
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
     * Return an unique instance of Router.
     */
    public static function getInstance(): Router
    {
        if (null === self::$_instance) {
            self::$_instance = new Router();
        }

        return self::$_instance;
    }
}
