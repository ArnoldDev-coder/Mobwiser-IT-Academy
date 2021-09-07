<?php

namespace Kernel\Database;

use Pagerfanta\Adapter\AdapterInterface;
use PDO;

class PaginatedQuery implements AdapterInterface
{
    private $query;

    public function __construct(Query $query)
    {
        $this->query = $query;
    }

    /**
     * Returns the number of results for the list.
     */
    final public function getNbResults(): int
    {
        return $this->query->count();
    }

    /**
     * Returns an slice of the results representing the current page of items in the list.
     */
    final public function getSlice(int $offset, int $length): iterable
    {
        $query = clone $this->query;
        return $query->limit($length, $offset)->fetchAll();
    }
}