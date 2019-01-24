<?php

namespace Piface\Router;

use Piface\Router\Exception\MethodNotAllowedException;
use Psr\Http\Message\ServerRequestInterface;

class Router implements RouterInterface
{
    /**
     * @var RouterContainer
     */
    private $routeContainer;

    public function __construct()
    {
        $this->routeContainer = new RouterContainer();
    }

    /**
     * Register a GET route.
     *
     * @param callable|string $action
     */
    public function get(string $path, string $name, $action): Route
    {
        $route = $this->createRoute($path, $name, $action);
        $route->allows('GET');
        return $this->routeContainer->addRoute($route);
    }

    /**
     * Compare the given request with routes in the routerContainer.
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        foreach ($this->routeContainer->getRoutes() as $route) {

            if ($this->routeContainer->match($request, $route)) {
                if (!in_array($request->getMethod(), $route->getAllows())) {
                    throw new MethodNotAllowedException($route->getAllows(), $request->getUri()->getPath());
                }

                return $route;
            }
        }

        return null;
    }
    
    public function getRoutes()
    {
        return $this->routeContainer->getRoutes();
    }

    /**
     * Create a new route.
     *
     * @param callable|string $action
     */
    private function createRoute(string $path, string $name, $action): Route
    {
        return new Route($path, $name, $action);
    }
}
