<?php
namespace  App\Admin\Actions;

use Kernel\Renderer\Renderer;

class AdminDashboard
{
    public function __construct(private Renderer $renderer)
    {
    }

    public function __invoke(): string
    {
        return $this->renderer->render('@admin/index');
    }
}