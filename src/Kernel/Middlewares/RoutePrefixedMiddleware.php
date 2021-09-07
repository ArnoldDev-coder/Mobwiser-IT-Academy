<?php

namespace Kernel\Middlewares;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RoutePrefixedMiddleware implements MiddlewareInterface
{
    private ContainerInterface $container;
    private string $routePrefix;
    private string $middleware;

    /**
     * @param ContainerInterface $container
     * @param string $routePrefix
     * @param string $middleware
     */
    public function __construct(
        ContainerInterface $container,
        string             $middleware,
        string             $routePrefix
    )
    {
        $this->container = $container;
        $this->routePrefix = $routePrefix;
        $this->middleware = $middleware;
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        if (str_starts_with($path, $this->routePrefix)) {
            return $this->container->get($this->middleware)->process($request, $handler);
        }
        return $handler->handle($request);
    }
}