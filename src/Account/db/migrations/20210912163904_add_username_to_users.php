<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddUsernameToUsers extends AbstractMigration
{
    public function change(): void
    {
        $this->table('users')
            ->addColumn('username', 'string')
            ->update();
    }
}
