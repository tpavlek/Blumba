<?php

namespace Depotwarehouse\Blumba\Domain\Traits;

use Depotwarehouse\Blumba\Domain\EntityCouldNotApplyEventException;
use Depotwarehouse\Blumba\EventSourcing\SerializableEventInterface;

trait AppliesEvents
{
    /**
     * Given a particular event, determine what its semantic `apply` method will be called.
     *
     * Convention dictates that `SomethingHappenedEvent` will have an apply method of `applySomethingHappened`
     *
     * Will return a callable which is either the apply method requested, or an anonymous function throwing an exception
     *
     * @param \Depotwarehouse\Blumba\EventSourcing\SerializableEventInterface $event
     * @return callable
     */
    private function calculateApplyMethod(SerializableEventInterface $event)
    {
        // An event will be named SomeXyzEvent, we need to remove the "event" (5 characters) from the end.
        $shortEventName = (new \ReflectionClass($event))->getShortName();
        $shortEventName = substr($shortEventName, 0, strlen($shortEventName) - 5);

        $methodName = "apply" . $shortEventName;

        if (!method_exists($this, $methodName)) {
            return function(SerializableEventInterface $event) use ($methodName) {
                throw new EntityCouldNotApplyEventException($this, $event);
            };
        }

        return function(SerializableEventInterface $event) use ($methodName) {
            return $this->{$methodName}($event);
        };
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
        $applyMethod = $this->calculateApplyMethod($event);

        return $applyMethod($event);
    }
}
