<?php

namespace Kernel\Database;

use PDO;
use stdClass;

/**
 * Class Table
 * @package Kernel\Database
 */
class Table
{
    public string $table;
    /**
     * @var PDO|null
     */
    public PDO|null $pdo;
    public $entity = stdClass::class;

    /**
     * bedroomsTable constructor.
     * @param PDO|null $pdo
     */
    public function __construct(?PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function makeQuery(): Query
    {
        return (new Query($this->pdo))->from($this->table, 'r')
            ->into($this->entity);
    }

    /**
     * Récupère une liste clef valeur de nos enregistrements
     */
    public function findList(): array
    {
        $results = $this->pdo
            ->query("SELECT id, name FROM $this->table")
            ->fetchAll(PDO::FETCH_NUM);
        $list = [];
        foreach ($results as $result) {
            $list[$result[0]] = $result[1];
        }
        return $list;
    }

    public function findAll(): Query
    {
        return $this->makeQuery();
    }

    public function findBy(string $field, string $value): mixed
    {
        return $this->makeQuery()->where("$field = :field")
            ->params(['field' => $value])
            ->fetchOrFail();
    }

    /**
     * Récupère un élément à partir de son ID
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->makeQuery()->where("id = $id")->fetchOrFail();
    }

    public function count(): string
    {
        return $this->makeQuery()->count();
    }

    /**
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function update(int $id, array $params): bool
    {
        $fieldQuery = $this->buildFieldQuery($params);
        $params['id'] = $id;
        $statement = $this->pdo->prepare("UPDATE $this->table SET $fieldQuery WHERE id = :id");
        return $statement->execute($params);
    }
    public function updateWhere(array $params, int $id): bool
    {
        $fieldQuery = $this->buildFieldQuery($params);
        $params['id'] = $id;
        $statement = $this->pdo->prepare
            ("UPDATE $this->table SET $fieldQuery   WHERE user_id = :id");
        return $statement->execute($params);
    }


    public function insert(array $params): bool
    {
        $fields = array_keys($params);
        $values = join(', ', array_map(function ($field) {
            return ':' . $field;
        }, $fields));
        $fields = join(', ', $fields);
        $statement = $this->pdo->prepare("INSERT INTO $this->table ($fields) VALUES ($values)");
        return $statement->execute($params);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare("DELETE FROM $this->table WHERE id = ?");
        return $statement->execute([$id]);
    }

    public function findDue()
    {
        $query = $this->pdo->prepare("SELECT SUM(due) FROM $this->table");
        $query->execute();
        return $query->fetchColumn();
    }

    /**
     * @param array $params
     * @return string
     */
    private function buildFieldQuery(array $params): string
    {
        return join(', ', array_map(function ($field) {
            return "$field = :$field";
        }, array_keys($params)));
    }

    public function paginationQuery(): string
    {
        return "SELECT * FROM $this->table";
    }

    /**
     * @return mixed
     */
    final public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    final public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * verifie qu'un enregistrement existe
     * @param int $id
     * @return bool
     */
    public function exists(mixed $id): bool
    {
        $statement = $this->pdo->prepare("SELECT id FROM $this->table WHERE id = ? ");
        $statement->execute([$id]);
        return $statement->fetchColumn() !== false;
    }

    public function fectchOrFail(string $query, array $params = []): mixed
    {
        $query = $this->pdo->prepare($query);
        $query->execute($params);
        if ($this->entity) {
            $query->setFetchMode(PDO::FETCH_CLASS, $this->entity);
        }
        $record = $query->fetch();
        if ($record === false) {
            return new NoRecodrException();
        }
        return $record;
    }

    private function fetchColumn(string $query, array $params = []): string
    {
        $query = $this->pdo->prepare($query);
        $query->execute($params);
        if ($this->entity) {
            $query->setFetchMode(PDO::FETCH_CLASS, $this->entity);
        }
        return $query->fetchColumn();

    }


}