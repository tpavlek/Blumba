<?php

namespace Depotwarehouse\Blumba\ReadModel;

use Depotwarehouse\Blumba\Domain\EntityConstructorInterface;
use Depotwarehouse\Blumba\Domain\Reconstituteable;
use Illuminate\Database\ConnectionInterface;

abstract class ReadModelRepository
{

    protected $connection;
    protected $constructor;

    protected function __construct(ConnectionInterface $connection, Reconstituteable $constructor)
    {
        $this->connection = $connection;
        $this->constructor = $constructor;
    }

    public static function connect(ConnectionInterface $connection, Reconstituteable $constructor)
    {
        return new static($connection, $constructor);
    }

    /**
     * Get the table name that represents the primary representation of this Read Model.
     *
     * @return string
     */
    protected abstract function getTableName();

    protected function getTable()
    {
        return $this->connection->table($this->getTableName());
    }

    public function find($id)
    {
        $data = $this->getTable()->where('id', '=', $id)->get();

        return $this->constructor->createInstance((array)$data);
    }
}
