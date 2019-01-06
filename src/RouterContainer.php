<?php

namespace Piface\Router;

use Piface\Router\Exceptions\DuplicateRouteException;
use Psr\Http\Message\ServerRequestInterface;

class RouterContainer
{
    /**
     * All routes sort by method.
     *
     * @var array
     */
    private $routes = [];

    /**
     * An array of all routes.
     *
     * @var Route[]
     */
    private $allRoutes = [];

    /**
     * index all routes which hav been registered to not have duplication.
     *
     * @var array
     */
    private $uri = [];

    /**
     * @param Route $route
     *
     * @return Route
     */
    public function addRoute(Route $route): Route
    {
        if (\in_array($route->getUri(), $this->uri, true)) {
            throw DuplicateRouteException::duplicateUri($route->getUri());
        }

        if (array_key_exists($route->getName(), $this->uri)) {
            throw DuplicateRouteException::duplicateUriName($route->getName());
        }

        $this->uri[$route->getName()] = $route->getUri();

        foreach ($route->getMethods() as $method) {
            $this->routes[$method][$route->getName()] = $route;
        }

        $this->allRoutes[] = $route;

        return $route;
    }

    /**
     * Check if the current object route is matching the request route.
     *
     * @param ServerRequestInterface $request
     * @param Route                  $route
     *
     * @return bool
     */
    public function match(ServerRequestInterface $request, Route $route): bool
    {
        $uri = $request->getUri()->getPath();
        $path = $this->generatePath($route);

        if (!preg_match("#^$path$#i", $uri, $matches)) {
            return false;
        }

        array_shift($matches);
        $route->setParameters($matches);

        return true;
    }

    /**
     * @param $method
     *
     * @return Route[]
     */
    public function getRoutesForSpecificMethod($method): array
    {
        if (array_key_exists($method, $this->routes)) {
            return $this->routes[$method];
        }

        return [];
    }

    /**
     * @return array
     */
    public function getRoutesByMethod(): array
    {
        return $this->routes;
    }

    /**
     * @return array
     */
    public function getAllRoutes(): array
    {
        return $this->allRoutes;
    }

    private function generatePath($route)
    {
        $path = $route->getUri();

        if (!empty($route->getWhere())) {
            foreach ($route->getWhere() as $attribute => $where) {
                $path = preg_replace('#{('.$attribute.')}#', '('.$where.')', $path);
            }
        }
        $path = preg_replace("#{([\w]+)}#", '([^/]+)', $path);

        return $path;
    }
}
