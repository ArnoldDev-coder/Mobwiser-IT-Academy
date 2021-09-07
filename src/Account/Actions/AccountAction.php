<?php

namespace App\Account\Actions;

use App\Authentification\Actions\Authentification;
use Kernel\Renderer\Renderer;


class AccountAction
{
    private Renderer $renderer;
    private Authentification $auth;

    public function __construct(Renderer $renderer, Authentification $auth)
    {
        $this->renderer = $renderer;
        $this->auth = $auth;
    }
    public function __invoke(): string
    {
        $user = $this->auth->getUser();
       return  $this->renderer->render('@account/profile', compact('user'));
    }
}