<?php

namespace Kernel\Middlewares;


use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;

class TrailingSlashMiddleware
{
    public function __invoke(ServerRequest $request, callable $next): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            return (new Response())->withStatus(301)
                ->withHeader('Location', substr($uri,  0,-1));
        }
        return $next($request);
    }
}