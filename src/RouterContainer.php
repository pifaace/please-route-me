<?php

namespace Piface\Router;

use Piface\Router\Exception\DuplicateRouteNameException;
use Piface\Router\Exception\DuplicateRoutePathException;
use Piface\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

class RouterContainer
{
    /**
     * Array of route objects.
     *
     * @var Route[]
     */
    private $routes = [];

    /**
     * index all routes which have been registered to avoid duplication.
     * The array is built like this : ['route_name' => 'route_path']
     *
     * @var array
     */
    private $paths = [];

    public function addRoute(Route $route): Route
    {
        if (\in_array($route->getPath(), $this->paths, true)) {
            throw new DuplicateRoutePathException($route->getPath());
        }

        if (array_key_exists($route->getName(), $this->paths)) {
            throw new DuplicateRouteNameException($route->getName());
        }

        $this->paths[$route->getName()] = $route->getPath();
        $this->routes[$route->getName()] = $route;

        return $route;
    }

    /**
     * Check if the current object route is matching the request route.
     */
    public function match(ServerRequestInterface $request, Route $route): bool
    {
        $requestedPath = $request->getUri()->getPath();
        $path = $this->argsResolver($route);

        if (!preg_match("#^$path$#i", $requestedPath, $matches)) {
            return false;
        }

        array_shift($matches);
        $route->setParameters($matches);

        return true;
    }

    /**
     * @return string|Exception
     */
    public function generatePath(string $name, $params = [])
    {
        return $this->getRouteByName($name)->generatePath($params);
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @return Route|Exception
     */
    public function getRouteByName(string $name)
    {
        if (!array_key_exists($name, $this->paths)) {
            throw new RouteNotFoundException($name);
        }

        return $this->routes[$name];
    }

    private function argsResolver(Route $route): string
    {
        $path = $route->getPath();

        if (!empty($route->getWhere())) {
            foreach ($route->getWhere() as $attribute => $where) {
                $path = preg_replace('#{('.$attribute.')}#', '('.$where.')', $path);
            }
        }
        $path = preg_replace("#{([\w]+)}#", '([^/]+)', $path);

        return $path;
    }
}
