<?php

namespace Piface\Router\Exception;

use Piface\Router\Exception;

class RouteNotFoundException extends Exception
{
    public function __construct(string $name)
    {
        $message = sprintf("No route found for '%s'.", $name);
        parent::__construct($message);
    }
}
