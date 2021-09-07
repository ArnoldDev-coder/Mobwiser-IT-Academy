<?php
namespace App\Account;

use App\Account\Actions\AccountAction;
use App\Account\Actions\AccountEditAction;
use App\Account\Actions\SignupAction;
use Kernel\Middlewares\LoggedInMiddleware;
use Kernel\Modules;
use Kernel\Renderer\Renderer;
use Kernel\Router\Router;
use Psr\Container\ContainerInterface;

class AccountModule extends  Modules
{
    public const  DEFINITIONS = __DIR__ . '/config.php';
    public const MIGRATIONS = __DIR__ . '/db/migrations';

    public function __construct(ContainerInterface $container)
    {
        $router =  $container->get(Router::class);
        $renderer = $container->get(Renderer::class);
        $renderer->addPath('account', __DIR__ . '/views');
        $router->get($container->get('account.prefix'), SignupAction::class, 'signup');
        $router->post($container->get('account.prefix'), SignupAction::class);
        $router->get($container->get('account.profile'), [LoggedInMiddleware::class, AccountAction::class], 'account');
        $router->post($container->get('account.profile'), [LoggedInMiddleware::class, AccountEditAction::class]);
    }
}

