<?php

namespace Depotwarehouse\Blumba\ReadModel;

use Carbon\Carbon;
use Depotwarehouse\Blumba\Domain\Entity;
use Depotwarehouse\Blumba\Domain\EntityConstructorInterface;
use Depotwarehouse\Blumba\Domain\EntityInterface;
use Depotwarehouse\Blumba\Domain\Reconstituteable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock;

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

    /**
     *
     *
     * Optionally you may pass a $recordLocator Closure that specifies how we determine which row we wish to update.
     * The default implementation uses the `id` column and the `->getId()` method of the entity to find the row, but
     * if you had a different key column you could pass something like
     *
     * ```php
     * // $recordLocator
     * function(Builder $query) use ($entity) {
     *    $query->where('other_key_column', '=', $entity->getOtherColumn()->serialize());
     * };
     *
     * @param $id
     * @param \Closure $recordLocator
     * @return EntityInterface
     * @throws NotFoundException
     */
    public function find($id, \Closure $recordLocator = null)
    {
        if ($recordLocator === null) {
            $recordLocator = function (Builder $query) use ($id) {
                return $query->where('id', '=', $id);
            };
        }

        $data = (array)$this->getTable()->where($recordLocator)->first();
        if ($data === null || $data == []) {
            throw new NotFoundException($this->getTableName(), "ID: {$id}");
        }

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

    /**
     * @param $object
     * @return mixed The id of the newly inserted object
     */
    public function insert($object)
    {
        $data = $this->mapToInsert($object);
        $this->callGuards($data);
        return $this->getTable()->insertGetId($data);
    }

    /**
     * Update something //TODO.
     *
     * @see self::find() for documentation on $recordLocator
     *
     * @param EntityInterface $entity
     * @param \Closure|null $recordLocator
     * @return int
     */
    public function update(EntityInterface $entity, \Closure $recordLocator = null)
    {
        if ($recordLocator === null) {
            $recordLocator = function(Builder $query) use ($entity) {
                $query->where('id', '=', $entity->getId()->toString());
            };
        }
        $attributes = [ ];
        foreach ($entity->getDirty() as $dirtyAttributeName) {

            $reflection = new \ReflectionClass($entity);
            $reflectionProperty = $reflection->getProperty($dirtyAttributeName);
            $docblock = new DocBlock($reflectionProperty);
            $serializeTag = $docblock->getTagsByName(Entity::SERIALIZE_DOC_TAG);

            if (count($serializeTag) > 0) {
                $serialize_attribute_name = $serializeTag[0]->getContent();
            } else {
                $serialize_attribute_name = $dirtyAttributeName;
            }

            //TODO transition off carbon onto Date/DateTime wrappers, and remove this.
            if ($entity->{$dirtyAttributeName} instanceof Carbon) {
                $attributes[$dirtyAttributeName] = $entity->{$dirtyAttributeName}->toDateString();
                continue;
            }
            $dirtyAttribute = $entity->{$dirtyAttributeName};

            // Entities are serialized by relation, so we'll store the ID in the entity_name_id field.
            if ($dirtyAttribute instanceof Entity) {
                $attributes[$serialize_attribute_name . "_id"] = $dirtyAttribute->getId()->toString();
                continue;
            }

            // Any booleans can be serialized as they are
            if (is_bool($dirtyAttribute)) {
                $attributes[$serialize_attribute_name] = $dirtyAttribute;
                continue;
            }

            $attributes[$serialize_attribute_name] = $entity->{$dirtyAttributeName}->serialize();
        }

        $result = $this->getTable()->where($recordLocator)->update($attributes);
        $entity->clean();
        return $result;
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

    /**
     * Given an array of stdClass objects from the database, collect them and instantiate the appropriate domain objects
     *
     * @param array $queryData
     * @return \Illuminate\Support\Collection
     */
    protected function collect(array $queryData)
    {
        if (count($queryData) == 0) {
            return new Collection();
        }

        $result = new Collection();

        foreach ($queryData as $getData) {
            $data = (array)$getData;

            $result->push($this->constructInstance($data));
        }

        return $result;
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
