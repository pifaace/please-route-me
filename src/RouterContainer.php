<?php

namespace Piface\Router;

use Piface\Router\Exception\DuplicateRouteNameException;
use Piface\Router\Exception\DuplicateRouteUriException;
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
        $this->routes[$route->getName()] = $route;

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
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    private function generatePath(Route $route): string
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
