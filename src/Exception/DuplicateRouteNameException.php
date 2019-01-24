<?php

namespace Piface\Router\Exception;

use Piface\Router\Exception;

class DuplicateRouteNameException extends Exception
{
    public function __construct(string $name)
    {
        $message = sprintf("Route name '%s' is already defined.", $name);

        parent::__construct($message);
    }
}
