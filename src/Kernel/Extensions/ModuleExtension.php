<?php

namespace Kernel\Extensions;

use Framework\App;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ModuleExtension extends AbstractExtension
{


    private App $app;

    public function __construct(App $app)
    {

        $this->app = $app;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('has_module', [$this->app, 'charged'])
        ];
    }

}