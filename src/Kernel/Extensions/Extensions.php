<?php

namespace Kernel\Extensions;

use Kernel\Router\Router;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Extensions extends AbstractExtension
{
    private Router $router;

    public function __construct(Router $router)
    {

        $this->router = $router;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('path', [$this, 'pathFor']),
            new TwigFunction('is_subpath', [$this, 'childOf'])
        ];
    }

    public function pathFor(string $path, array $params = []): string
    {
        return $this->router->generateUri($path, $params);
    }

    public function childOf(string $path):bool
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $excpectedUri = $this->router->generateUri($path);
        return str_contains($uri, $excpectedUri) !== false;
    }
}