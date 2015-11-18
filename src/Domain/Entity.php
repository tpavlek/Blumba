<?php

namespace Depotwarehouse\Blumba\Domain;

use Carbon\Carbon;
use Depotwarehouse\Blumba\EventSourcing\SerializableEventInterface;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock;

abstract class Entity implements EntityInterface
{

    use Traits\AppliesEvents;

    const SERIALIZE_DOC_TAG = "serializes";

    protected $dirty = [];

    public function __get($attribute)
    {
        if (!isset($this->{$attribute})) {
            throw new \Exception("Could not access {$attribute} on " . get_class($this));
        }

        if (Str::startsWith($attribute, "is_")) {
            $getMethod = camel_case($attribute);
        } else {
            $getMethod = "get" . ucfirst(camel_case($attribute));
        }

        return $this->{$getMethod}();
    }

    public function __isset($attribute)
    {
        $getMethod = "get" . ucfirst(camel_case($attribute));

        if (method_exists($this, $getMethod)) {
            return true;
        }
    }

    /**
     * Compute equality of the identity of this entity with another entity.
     *
     * @param EntityInterface $otherEntity
     * @return boolean
     */
    public function equals(EntityInterface $otherEntity)
    {
        return ($otherEntity instanceof static) && $this->getId()->toString() === $otherEntity->getId()->toString();
    }



    protected function setAttribute($attribute_name, $new_value)
    {
        $this->{$attribute_name} = $new_value;
        $this->dirty[] = $attribute_name;
    }

    public function syncAttributes(array $attributes)
    {
        foreach ($attributes as $key => $attribute)
        {
            /** @var ValueObjectInterface|bool $currentAttribute */
            $currentAttribute = $this->{$key};

            // If our attribute is a raw boolean, we'll compare it directly.
            if (is_bool($currentAttribute)) {
                if ($currentAttribute !== $attribute) {
                    $this->setAttribute($key, $attribute);
                }
                continue;
            }

            // Otherwise it's a value object and we'll compare it according to its interface
            if (!$currentAttribute->equals($attribute)) {
                $this->setAttribute($key, $attribute);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function isDirty()
    {
        return count($this->dirty) > 0;
    }

    /**
     * @inheritDoc
     */
    public function getDirty()
    {
        return $this->dirty;
    }

    /**
     * @inheritDoc
     */
    public function clean()
    {
        $this->dirty = [];
    }


    /**
     * Serializes all the dirty attributes of the Entity.
     *
     * Any sub-entities will be serialized in the format sub_entity_id => $id. All value objects will be serialzed as
     * property_name => $serialize_value
     *
     * @return array
     */
    public function serialize()
    {
        $attributes = [];

        foreach ($this->getDirty() as $dirtyAttributeName) {

            $reflection = new \ReflectionClass($this);
            $reflectionProperty = $reflection->getProperty($dirtyAttributeName);
            $docblock = new DocBlock($reflectionProperty);
            $serializeTag = $docblock->getTagsByName(self::SERIALIZE_DOC_TAG);

            if (count($serializeTag) > 0) {
                $serialize_attribute_name = $serializeTag[0]->getContent();
            } else {
                $serialize_attribute_name = $dirtyAttributeName;
            }

            //TODO transition off carbon onto Date/DateTime wrappers, and remove this.
            if ($this->{$dirtyAttributeName} instanceof Carbon) {
                $attributes[$dirtyAttributeName] = $this->{$dirtyAttributeName}->toDateString();
                continue;
            }
            $dirtyAttribute = $this->{$dirtyAttributeName};

            // Entities are serialized by relation, so we'll store the ID in the entity_name_id field.
            if ($dirtyAttribute instanceof Entity) {
                $attributes[$dirtyAttributeName . "_id"] = $dirtyAttribute->getId()->toString();
                continue;
            }

            $attributes[$serialize_attribute_name] = $this->{$dirtyAttributeName}->serialize();
        }

        return $attributes;
    }
}
