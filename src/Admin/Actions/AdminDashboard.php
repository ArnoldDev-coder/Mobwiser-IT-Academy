<?php

namespace App\Admin\Actions;

use App\Account\Table\UserTable;
use Kernel\Renderer\Renderer;

class AdminDashboard
{
    private Renderer $renderer;
    private UserTable $userTable;

    public function __construct(Renderer $renderer, UserTable $userTable)
    {
        $this->renderer = $renderer;
        $this->userTable = $userTable;
    }

    public function __invoke(): string
    {
        $users = $this->userTable->count();
        $due = $this->userTable->findDue();
        return $this->renderer->render('@admin/widget', compact('users', 'due'));
    }
}