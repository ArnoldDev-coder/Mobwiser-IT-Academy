<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddNameToUsers extends AbstractMigration
{
    public function change(): void
    {
        $this->table('users')
            ->insert([
                'username' => 'admin',
                'email' => 'admin@contact.com',
                'password' => password_hash('admin', PASSWORD_DEFAULT),
            ])
            ->save();
    }
}
