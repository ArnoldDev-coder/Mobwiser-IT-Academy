<?php

namespace Kernel\Renderer;

use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class RendererFactory
{
    public function __invoke(ContainerInterface $container): Renderer
    {
        $viewPath = $container->get('viewPath');
        $loader = new FilesystemLoader($viewPath);
        $twig = new Environment($loader, ['debug'=> true]);
        $twig->addExtension(new DebugExtension());
        if ($container->has('extensions')) {
            foreach ($container->get('extensions') as $extension) {
                $twig->addExtension($extension);
            }
        }
        return new Renderer($loader, $twig);
    }
}