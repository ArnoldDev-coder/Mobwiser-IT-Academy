<?php
namespace App\Account\Table;

use App\Account\Entity\UserEntity;
use Kernel\Database\Query;
use Kernel\Database\Table;
use PDO;

class UserTable extends Table
{
    public string $table = 'users';

    public function __construct(?PDO $pdo, string $entity = UserEntity::class)
    {
        $this->entity = $entity;
        parent::__construct($pdo);
    }
    public function findAll(): Query
    {
        return $this->makeQuery();
    }
}