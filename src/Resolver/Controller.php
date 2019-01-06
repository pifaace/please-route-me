<?php

namespace Piface\Routing;

use Piface\Routing\Exceptions\HttpResponseException;
use GuzzleHttp\Psr7\Response;

class Controller
{
    /**
     * @param Route $route
     * @return mixed
     *
     * @throws HttpResponseException
     */
    public function callAction(Route $route)
    {
        $exploded = explode('@', $route->getAction());
        $controllerName = array_shift($exploded);
        $method = end($exploded);

        $controller = new $controllerName();
        $response = call_user_func_array([$controller, $method], $route->getParameters());

        if (!$response instanceof Response) {
            throw new HttpResponseException(sprintf(
                '%s::%s must return a Response object.', static::class, $method
            ));
        }

        return $response;
    }

    /**
     * Handle a no existing method
     *
     * @param $name
     * @param $arguments
     *
     * @throws \BadMethodCallException
     */
    public function __call($name, $arguments)
    {
        throw new \BadMethodCallException(sprintf(
                'Method %s does not exist in %s', $name, static::class)
        );
    }
}
