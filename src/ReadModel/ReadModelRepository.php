<?php

namespace Depotwarehouse\Blumba\ReadModel;

use Depotwarehouse\Blumba\Domain\EntityConstructorInterface;
use Depotwarehouse\Blumba\Domain\EntityInterface;
use Depotwarehouse\Blumba\Domain\Reconstituteable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class ReadModelRepository
{

    protected $connection;
    protected $constructor;

    public function __construct(ConnectionInterface $connection, Reconstituteable $constructor)
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

    /**
     * Map the given object into an array of key => value pairs for use with inserting into the database.
     *
     * @param $object
     * @return array
     */
    protected abstract function mapToInsert($object);

    protected function getTable()
    {
        return $this->connection->table($this->getTableName());
    }

    public function all()
    {
        $collection = new Collection();
        foreach ($this->getTable()->get() as $data) {
            $data = (array)$data;
            $this->callLoads($data);
            $collection->push($this->constructor->createInstance($data));
        }

        return $collection;
    }

    public function find($id)
    {
        $data = (array)$this->getTable()->where('id', '=', $id)->first();
        $this->callLoads($data);
        return $this->constructor->createInstance((array)$data);
    }

    public function findAll(array $ids)
    {
        $found = new Collection();

        foreach ($ids as $id) {
            $found->push($this->find($id));
        }

        return $found;
    }

    public function insert($object)
    {
        $data = $this->mapToInsert($object);
        $this->callGuards($data);
        return $this->getTable()->insert($data);
    }

    public function update(EntityInterface $entity)
    {
        $attributes = [ ];
        foreach ($entity->getDirty() as $dirtyAttribute) {
            $attributes[$dirtyAttribute] = $entity->{$dirtyAttribute}->serialize();
        }

        return $this->getTable()->where('id', '=', $entity->getId()->toString())->update($attributes);
    }

    public function remove(EntityInterface $entity)
    {
        return $this->getTable()->delete($entity->getId()->toString());
    }

    /**
     * @param array $data
     * @return EntityInterface
     */
    protected function constructInstance(array &$data)
    {
        $this->callLoads($data);
        return $this->constructor->createInstance($data);
    }

    protected function callLoads(array &$data)
    {
        $reflection = new \ReflectionClass($this);

        foreach ($reflection->getMethods() as $method) {
            /** @var \ReflectionMethod $method */

            // We're looking for a method that starts with "load" and takes a single array as a parameter.
            if (Str::startsWith($method->getName(), "load")) {
                if ($method->getNumberOfParameters() == 1 && $method->getParameters()[0]->isArray()) {
                    // We've found a guard method, let's call it.
                    $this->{$method->getName()}($data);
                }
            }
        }
    }

    protected function callGuards(array $data)
    {
        $reflection = new \ReflectionClass($this);

        foreach ($reflection->getMethods() as $method) {
            /** @var \ReflectionMethod $method */

            // We're looking for a method that starts with "guard" and takes a single array as a parameter.
            if (Str::startsWith($method->getName(), "guard")) {
                if ($method->getNumberOfParameters() == 1 && $method->getParameters()[0]->isArray()) {
                    // We've found a guard method, let's call it.
                    $this->{$method->getName()}($data);
                }
            }
        }
    }
}
