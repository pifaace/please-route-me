<?php

namespace Piface\Router;

use Piface\Router\Exception\DuplicateRouteNameException;
use Piface\Router\Exception\DuplicateRouteUriException;
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
     * index all routes which have been registered to avoid duplication.
     *
     * @var array
     */
    private $path = [];

    public function addRoute(Route $route): Route
    {
        if (\in_array($route->getPath(), $this->path, true)) {
            throw new DuplicateRouteUriException($route->getPath());
        }

        if (array_key_exists($route->getName(), $this->path)) {
            throw new DuplicateRouteNameException($route->getName());
        }

        $this->path[$route->getName()] = $route->getPath();
        $this->routes[$route->getMethod()][$route->getName()] = $route;
        $this->allRoutes[] = $route;

        return $route;
    }

    /**
     * Check if the current object route is matching the request route.
     */
    public function match(ServerRequestInterface $request, Route $route): bool
    {
        $requestedPath = $request->getUri()->getPath();
        $path = $this->generatePath($route);

        if (!preg_match("#^$path$#i", $requestedPath, $matches)) {
            return false;
        }

        array_shift($matches);
        $route->setParameters($matches);

        return true;
    }

    /**
     * @return Route[]|[]
     */
    public function getRoutesForSpecificMethod(string $method): array
    {
        if (array_key_exists($method, $this->routes)) {
            return $this->routes[$method];
        }

        return [];
    }

    /**
     * Return an array sorted by methods.
     */
    public function getRoutesByMethod(): array
    {
        return $this->routes;
    }

    public function getAllRoutes(): array
    {
        return $this->allRoutes;
    }

    private function generatePath(Route $route)
    {
        $path = $route->getPath();

        if (!empty($route->getWhere())) {
            foreach ($route->getWhere() as $attribute => $where) {
                $path = preg_replace('#{(' . $attribute . ')}#', '(' . $where . ')', $path);
            }
        }
        $path = preg_replace("#{([\w]+)}#", '([^/]+)', $path);

        return $path;
    }
}
