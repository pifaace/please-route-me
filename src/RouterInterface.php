<?php

namespace Piface\Router;

use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    /**
     * Register a GET route.
     *
     * @param callable|string $action
     */
    public function get(string $path, string $name, $action): Route;

    /**
     * Compare the given request with routes in the routerContainer.
     */
    public function match(ServerRequestInterface $request): ?Route;
}
