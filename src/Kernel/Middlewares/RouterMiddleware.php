<?php

namespace Kernel\Middlewares;

use GuzzleHttp\Psr7\ServerRequest;
use Kernel\Router\Router;
use Psr\Http\Message\ResponseInterface;

class RouterMiddleware
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function __invoke(ServerRequest $request, callable $next): ResponseInterface
    {
        $route = $this->router->match($request);
        if (is_null($route)) {
            return $next($request);
        }
        $params = $route->getParams();
        $request = array_reduce(array_keys($params), static function ($request, $key) use ($params) {
            return $request->withAttribute($key, $params[$key]);
        }, $request);
        $request = $request->withAttribute(get_class($route), $route);
        return $next($request);
    }
}
