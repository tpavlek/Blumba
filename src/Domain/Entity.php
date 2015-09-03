<?php

namespace Depotwarehouse\Blumba\Domain;

use Depotwarehouse\Blumba\EventSourcing\SerializableEventInterface;

abstract class Entity implements EntityInterface
{

    const SERIALIZE_DOC_TAG = "serializes";

    protected $dirty = [];

    public function __get($attribute)
    {
        if (!isset($this->{$attribute})) {
            throw new \Exception("Could not access {$attribute} on " . get_class($this));
        }

        $getMethod = "get" . ucfirst(camel_case($attribute));
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

    /**
     * Given a particular event from the system, apply the changes in that event to this object.
     *
     * @param SerializableEventInterface $event
     * @return static
     * @throws EntityCouldNotApplyEventException
     */
    public function apply(SerializableEventInterface $event)
    {
        $methodName = $this->calculateCallableMethod("apply", $event);

        if ($methodName === null) {
            throw new EntityCouldNotApplyEventException($this, $event);
        }

        return $this->{$methodName}($event);
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
            if (!$this->{$key}->equals($attribute)) {
                $this->setAttribute($key, $attribute);
            }
        }
    }


    private function calculateCallableMethod($prefix, SerializableEventInterface $event)
    {
        // An event will be named SomeXyzEvent, we need to remove the "event" (5 characters) from the end.
        $shortEventName = (new \ReflectionClass($event))->getShortName();
        $shortEventName = substr($shortEventName, 0, strlen($shortEventName) - 5);

        $methodName = $prefix . $shortEventName;

        if (method_exists($this, $methodName)) {
            return $methodName;
        }

        return null;
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


}
