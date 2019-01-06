<?php

namespace Piface\Routing\Exceptions;

class DuplicateRouteException extends RoutingException
{
    public static function duplicateUriName($name)
    {
        return new static("Route name '".$name."' is already defined.");
    }

    public static function duplicateUri($uri)
    {
        return new static("URI '".$uri."' is already defined");
    }
}
