<?php
namespace App\Home;

use App\Home\Actions\HomeAction;
use Kernel\Modules;
use Kernel\Renderer\Renderer;
use Kernel\Router\Router;
use Psr\Container\ContainerInterface;

class HomeModule extends Modules
{
    public const MIGRATIONS =  __DIR__.'/db/migrations';
    public const SEEDS  =  __DIR__.'/db/seeds';

    public function __construct(ContainerInterface $container)
    {
        $router = $container->get(Router::class);
        $renderer = $container->get(Renderer::class);
        $renderer->addPath('home', __DIR__.'/views');
        $router->get('/', HomeAction::class, 'home');
    }
}