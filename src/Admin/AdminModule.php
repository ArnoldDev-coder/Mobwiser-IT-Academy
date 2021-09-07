<?php

namespace App\Admin;

use App\Admin\Actions\AdminDashboard;
use Kernel\Modules;
use Kernel\Renderer\Renderer;
use Kernel\Router\Router;
use Psr\Container\ContainerInterface;

class AdminModule extends Modules
{
    public const DEFINITIONS = __DIR__ . '/config.php';

    public function __construct(ContainerInterface $container)
    {
        $router = $container->get(Router::class);
        $renderer = $container->get(Renderer::class);
        $renderer->addPath('admin', __DIR__ . '/views');
        $router->get($container->get('admin.prefix'), AdminDashboard::class, 'admin');
    }
}