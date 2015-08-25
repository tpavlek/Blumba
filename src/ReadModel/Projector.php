<?php

namespace Depotwarehouse\Blumba\ReadModel;

use Depotwarehouse\Blumba\EventSourcing\SerializableEventInterface;

abstract class Projector implements ProjectorInterface
{

    public function project(SerializableEventInterface $event)
    {
        $methodName = $this->calculateCallableMethod("project", $event);

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
