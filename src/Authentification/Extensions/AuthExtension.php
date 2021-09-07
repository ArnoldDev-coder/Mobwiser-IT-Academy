<?php

namespace App\Authentification\Extensions;

use App\Authentification\Actions\Authentification;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AuthExtension extends AbstractExtension
{
    private Authentification $auth;

    public function __construct(Authentification $auth)
    {
        $this->auth = $auth;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('current_user', [$this->auth, 'getUser'])
        ];
    }

}