<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddUserTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('users')
            ->addColumn('name', 'string')
            ->addColumn('last_name', 'string')
            ->addColumn('email', 'string')
            ->addColumn('password', 'string')
            ->create();
    }
}
