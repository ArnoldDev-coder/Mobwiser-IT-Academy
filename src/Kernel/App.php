<?php

namespace Kernel;

use DI\ContainerBuilder;
use Exception;
use GuzzleHttp\Psr7\ServerRequest;
use Kernel\Middlewares\CombinedMiddleware;
use Kernel\Middlewares\RoutePrefixedMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class App
 * @package Kernel
 */
class App implements RequestHandlerInterface
{
    /**
     * @var array
     */
    private array $modules = [];
    private string $definition;
    /**
     * @var ContainerInterface|null
     */
    private $container;
    public $middlewares = [];
    private $index = 0;

    public function __construct(string $definition)
    {
        $this->definition = $definition;
    }

    public function addModule(string $module): self
    {
        $this->modules[] = $module;
        return $this;
    }

    public function make(string $middleware, string $routePrefix  = null ): self
    {
        if ($routePrefix === null) {
            $this->middlewares[] = $middleware;
        } else {
            $this->middlewares[] = new RoutePrefixedMiddleware($this->getContainer(), $middleware, $routePrefix);
        }
        return $this;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->index++;
        if ($this->index > 1){
            throw  new Exception();
        }
        $middlewre = new CombinedMiddleware($this->getContainer(), $this->middlewares);
        return $middlewre->process($request, $this);
    }

    public function run(ServerRequest $request): ResponseInterface
    {
        foreach ($this->modules as $module) {
            $this->getContainer()->get($module);
        }
        return $this->handle($request);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            $builder = new ContainerBuilder();
            $builder->addDefinitions($this->definition);

            foreach ($this->modules as $module) {
                if ($module::DEFINITIONS) {
                    $builder->addDefinitions($module::DEFINITIONS);
                }
            }

            $this->container = $builder->build();
        }
        return $this->container;
    }

    /**
     * @return array
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * @param array $modules
     */
    public function setModules(array $modules): void
    {
        $this->modules = $modules;
    }
    public function charged(string $module): ?string
    {
        if (array_key_exists($module, $this->modules)){
            return $module;
        }
        return  null;
    }
}