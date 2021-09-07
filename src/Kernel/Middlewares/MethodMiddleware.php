<?php

namespace Kernel\Middlewares;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;

class MethodMiddleware
{
    public function __invoke(ServerRequest $request, callable $next): ResponseInterface
    {
        $parseBody = $request->getParsedBody();
        if (array_key_exists("_METHOD", $parseBody) && in_array($parseBody['_METHOD'], ["DELETE", "PUT"])) {
            $request = $request->withMethod($parseBody['_METHOD']);
        }
        return $next($request);
    }
}