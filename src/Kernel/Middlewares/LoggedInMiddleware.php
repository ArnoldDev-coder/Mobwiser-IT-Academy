<?php

namespace Kernel\Middlewares;


use App\Authentification\Actions\Authentification;
use App\Authentification\Exception\ForbiddenException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoggedInMiddleware implements MiddlewareInterface
{
    private Authentification $auth;

    public function __construct(Authentification $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @throws ForbiddenException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $this->auth->getUser();
        if (is_null($user)){
            throw new ForbiddenException();
        }return $handler->handle($request->withAttribute('user', $user));
    }
}