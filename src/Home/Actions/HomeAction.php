<?php

namespace App\Home\Actions;

use Kernel\Renderer\Renderer;

class HomeAction
{

    public function __construct(private Renderer $renderer)
    {
    }

    public function __invoke(): string
    {
        return $this->renderer->render('@home/index');
    }
}