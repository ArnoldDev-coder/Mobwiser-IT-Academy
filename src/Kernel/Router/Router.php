<?php

namespace Kernel\Router;

use GuzzleHttp\Psr7\ServerRequest;
use Kernel\Router\Route\Route;
use Mezzio\Router\FastRouteRouter;
use Mezzio\Router\Route as FsRoute;


/**
 * Class router
 * @package Kernel\router
 */
class Router
{
    private FastRouteRouter $router;

    /**
     * router constructor.
     */
    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    public function crud(string $prefixPath, callable|string $callable, string $prefixName):void
    {
        $this->get($prefixPath, $callable, $prefixName.'.index');
        $this->get($prefixPath.'/{id:[\d]+}', $callable, $prefixName.'.edit');
        $this->post($prefixPath.'/{id:[\d]+}', $callable);
        $this->get($prefixPath.'/create', $callable, $prefixName.'.create');
        $this->post($prefixPath.'/create', $callable);
        $this->delete($prefixPath.'/{id:[\d]+}', $callable, $prefixName.".delete");
    }
    /**
     * @param string $path
     * @param mixed $callback
     * @param string|null $name
     */
    public function get(string $path, mixed $callback, ?string $name = null): void
    {
        $this->router->addRoute(new FsRoute($path, $callback, ['GET'], $name));
    }

    /**
     * @param string $path
     * @param mixed $callback
     * @param string|null $name
     */
    public function post(string $path, mixed $callback, ?string $name = null): void
    {
        $this->router->addRoute(new FsRoute($path, $callback, ['POST'], $name));
    }

    public function any(string $path, mixed $callback, ?string $name = null): void
    {
        $this->router->addRoute(new FsRoute($path, $callback, ['POST', 'GET', 'PUT', 'DELETE'], $name));
    }
    /**
     * @param string $path
     * @param mixed $callback
     * @param string|null $name
     */
    public function delete(string $path, mixed $callback, ?string $name = null): void
    {
        $this->router->addRoute(new FsRoute($path, $callback, ['DELETE'], $name));
    }

    /**
     * @param ServerRequest $request
     * @return route|null
     */
    public function match(ServerRequest $request): ?route
    {
        $route = $this->router->match($request);
        if ($route->isSuccess()) {
            return new Route($route->getMatchedRouteName()
                , $route->getMatchedRoute()->getMiddleware(), $route->getMatchedParams());
        }
        return null;
    }

    /**
     * @param string $path
     * @param array $params
     * @param array $queryParams
     * @return string
     */
    public function generateUri(string $path, array $params = [], array $queryParams = []): string
    {
        $uri = $this->router->generateUri($path, $params);
        if (!empty($queryParams)) {
            return $uri . '?' . http_build_query($queryParams);
        }
        return $uri;
    }
}