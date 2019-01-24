<?php

namespace Piface\Router\Exception;

use Piface\Router\Exception;

class DuplicateRouteUriException extends Exception
{
    public function __construct(string $uri)
    {
        $message = sprintf("URI '%s' is already defined", $uri);

        parent::__construct($message);
    }
}
