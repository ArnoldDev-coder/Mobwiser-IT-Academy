<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddRoleDueWhoInvite extends AbstractMigration
{
    public function change(): void
    {
        $this->table('users')
            ->addColumn('role', 'string', ['default' => 'user'])
            ->addColumn('due', 'integer', ['null'=> true])
            ->addColumn('who_invite', 'string', ['null' => true])
            ->update();
    }
}
