<?php

namespace Kernel\Database;

use PHPUnit\Util\Exception;

class QueryResult implements \ArrayAccess, \Iterator
{

    private array $records;
    private string $entity;
    private int $index;
    private array $hydatetedrecords = [];

    public function __construct(array $records, ?string $entity = null)
    {
        $this->records = $records;
        $this->entity = $entity;
    }

    final public function get(int $index)
    {
        if ($this->entity){
            if (!isset($this->hydatetedrecords[$index])){
                $this->hydatetedrecords[$index] = Hydrator::hydrate($this->records[$index], $this->entity);
            }
            return  $this->hydatetedrecords[$index];
        }
        return $this->entity;
    }
    final public function current()
    {
        return $this->get($this->index);
    }


    final public function next(): void
    {
        $this->index++;
    }


    public function key()
    {
        return $this->index;
    }


    public function valid()
    {
        return isset($this->records[$this->index]);
    }


    final public function rewind():void
    {
        $this->index = 0;
    }


    public function offsetExists($offset)
    {
        return isset($this->records[$offset]);
    }


    public function offsetGet($offset)
    {
        return $this->get($offset);
    }


    final public function offsetSet($offset, $value): void
    {
        throw new Exception('Can\'t alter records');
    }


    public function offsetUnset($offset): void
    {
        throw new Exception('Can\'t alter records');
    }
}