<?php
namespace App\Authentification\Actions;

use Kernel\Renderer\Renderer;
use Psr\Http\Message\ServerRequestInterface;

class LoginAction
{

    /**
     * @var Renderer
     */
    private $renderer;

    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request): string
    {
        return $this->renderer->render('@auth/login');
    }
}