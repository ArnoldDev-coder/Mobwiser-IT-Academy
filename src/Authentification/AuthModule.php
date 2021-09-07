<?php

namespace App\Authentification;

use App\Account\Actions\AttemptAction;
use App\Authentification\Actions\LoginAction;
use App\Authentification\Actions\LogoutAction;
use Kernel\Modules;
use Kernel\Renderer\Renderer;
use Kernel\Router\Router;
use Psr\Container\ContainerInterface;

class AuthModule extends Modules
{
    public const DEFINITIONS = __DIR__ . '/config.php';
    public const MIGRATIONS = __DIR__ . '/db/migrations';
    public const SEEDS = __DIR__ . '/db/seeds';

    public function __construct(ContainerInterface $container)
    {
        $renderer = $container->get(Renderer::class);
        $router = $container->get(Router::class);
        $renderer->addPath('auth', __DIR__ . '/views');
        $router->get($container->get('auth.login'), LoginAction::class, 'auth.login');
        $router->post($container->get('auth.login'), AttemptAction::class);
        $router->post($container->get('auth.logout'), LogoutAction::class, 'auth.logout');
    }
}