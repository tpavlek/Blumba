<?php

namespace Depotwarehouse\Blumba\Domain;

use Depotwarehouse\Blumba\EventSourcing\SerializableEventInterface;

abstract class Entity implements EntityInterface
{
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

        $this->{$methodName}($event);
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

}
