<?php

namespace Piface\Router\Exception;

use Piface\Router\Exception;

class MethodNotAllowedException extends Exception
{
    public function __construct(array $allow, string $path)
    {
        $message = sprintf("No route found for '%s'. Method not allowed (Allow: %s)", $path, implode(',', $allow));
        parent::__construct($message);
    }
}
