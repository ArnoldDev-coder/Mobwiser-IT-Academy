<?php
namespace App\Contact;

use App\Contact\Actions\ContactAction;
use Kernel\Modules;
use Kernel\Renderer\Renderer;
use Kernel\Router\Router;
use Psr\Container\ContainerInterface;

class ContactModule extends  Modules
{
    public const DEFINITIONS = __DIR__ . '/config.php';

    public function __construct(ContainerInterface $container)
    {
        $renderer = $container->get(Renderer::class);
        $router = $container->get(Router::class);
        $renderer->addPath('contact', __DIR__ . '/views');
        $router->get($container->get('contact.prefix'),  ContactAction::class, 'contact');
        $router->post($container->get('contact.prefix'),ContactAction::class );
    }
}