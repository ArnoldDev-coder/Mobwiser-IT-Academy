<?php
namespace Kernel\Router\Route;

class Route
{
    private string $name;
    private mixed $callback;
    private array $params;

    public function __construct(string $name, mixed $callback, array $params = [])
    {

        $this->name = $name;
        $this->callback = $callback;
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getCallback(): mixed
    {
        return $this->callback;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}