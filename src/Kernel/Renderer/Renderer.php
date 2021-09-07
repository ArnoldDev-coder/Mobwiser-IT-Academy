<?php

namespace Kernel\Renderer;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Renderer
{
    /**
     * @return Environment
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }
    private FilesystemLoader $loader;
    private Environment $twig;

    public function __construct(FilesystemLoader $loader, Environment $twig)
    {
        $this->loader = $loader;
        $this->twig = $twig;
    }

    public function addPath(string $namespace, string $path = null): void
    {
        $this->loader->addPath($path, $namespace);
    }

    public function render(string $view, array $params = []): string
    {
        return $this->twig->render($view . '.twig', $params);
    }

    public function addGlobal(string $key, mixed $value): void
    {
        $this->twig->addGlobal($key, $value);
    }

}