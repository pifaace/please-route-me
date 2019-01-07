<?php

namespace Piface\Router;

/**
 * Represent a register route.
 */
class Route
{
    /**
     * @var string
     */
    private $method;

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

    public function __construct(string $method, string $uri, string $name, $action)
    {
        $this->name = $name;
        $this->action = $action;
        $this->uri = $uri;
        $this->method = $method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

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

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    public function where(array $expressions)
    {
        $this->wheres = array_merge($this->wheres, $expressions);

        return $this;
    }

    public function getWhere(): array
    {
        return $this->wheres;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethods(string $method): void
    {
        $this->method = $method;
    }
}
