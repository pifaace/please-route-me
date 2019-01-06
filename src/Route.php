<?php

namespace Piface\Routing;

/**
 * Represent a register route.
 */
class Route
{
    /**
     * @var array
     */
    private $methods;

    /**
     * @var string
     */
    private $name;

    /**
     * @var callable|string
     */
    private $action;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var array
     */
    private $wheres = [];

    public function __construct($method, $uri, $name, $action)
    {
        $this->name = $name;
        $this->action = $action;
        $this->uri = $uri;
        $this->methods = $method;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return callable|string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    public function where($expressions)
    {
        $this->wheres = array_merge($this->wheres, $expressions);

        return $this;
    }

    public function getWhere(): array
    {
        return $this->wheres;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param array $methods
     */
    public function setMethods(array $methods): void
    {
        $this->methods = $methods;
    }
}
