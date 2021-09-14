<?php

namespace Kernel\Middlewares;

use App\Account\Entity\UserEntity;
use App\Authentification\Exception\ForbiddenException;
use Kernel\Response\RedirectResponse;
use Kernel\Session\FlashMessage;
use Kernel\Session\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ForbiddenMiddleware implements MiddlewareInterface
{
    private string $loginPath;
    private FlashMessage $flashMessage;
    private Session $session;

    public function __construct(string $loginPath, FlashMessage $flashMessage, Session $session)
    {
        $this->loginPath = $loginPath;
        $this->flashMessage = $flashMessage;
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ForbiddenException $exception) {
            return $this->redirectLogin($request);
        } catch (\TypeError $error) {
            $errors = $error->getMessage();
            if (str_contains($errors, UserEntity::class)) {
                return $this->redirectLogin($request);
            }
        }
    }

    public function redirectLogin(ServerRequestInterface $request): RedirectResponse
    {
        $this->session->set('auth.redirect', $request->getUri()->getPath());
        $this->flashMessage->error('Vous devez vous connecter pour acceder à cette page');
        return new RedirectResponse($this->loginPath);
    }


}