<?php

namespace Piface\Router;

/**
 * Represent a register route.
 */
class Route
{
    /**
     * List of allow HTTP verbs.
     *
     * @var array
     */
    private $allows = [];

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
    private $path;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var array
     */
    private $wheres = [];

    public function __construct(string $path, string $name, $action)
    {
        $this->name = $name;
        $this->action = $action;
        $this->path = $path;
    }

    /**
     * Set a where rule to your route.
     */
    public function where(array $expressions): Route
    {
        $this->wheres = array_merge($this->wheres, $expressions);

        return $this;
    }

    /**
     * @param string|array $allow
     */
    public function allows($allows): Route
    {
        $this->allows = array_merge($this->allows, (array) $allows);

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
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

    public function getWhere(): array
    {
        return $this->wheres;
    }

    public function getAllows(): array
    {
        return $this->allows;
    }

    /**
     * This method will generate a path and specify params value if it defined.
     */
    public function generatePath(array $params = []): string
    {
        return preg_replace_callback('/\{(.*?)\}/', function ($m) use (&$params) {
            if (isset($params[$m[1]])) {
                return $params[$m[1]];
            }

            return $m[0];
        }, $this->getPath());
    }
}
