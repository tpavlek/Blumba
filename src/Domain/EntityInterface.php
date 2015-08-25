<?php

namespace Depotwarehouse\Blumba\Domain;

use Depotwarehouse\Blumba\EventSourcing\SerializableEventInterface;
use Illuminate\Contracts\Support\Arrayable;
use League\Event\EventInterface;

interface EntityInterface extends Arrayable
{

    /**
     * Compute equality of the identity of this entity with another entity.
     *
     * @param EntityInterface $otherEntity
     * @return boolean
     */
    public function equals(EntityInterface $otherEntity);

    /**
     * Given a particular event from the system, apply the changes in that event to this object.
     *
     * @param SerializableEventInterface $event
     * @return static
     * @throws EntityCouldNotApplyEventException
     */
    public function apply(SerializableEventInterface $event);

    /**
     * @return IdValueInterface
     */
    public function getId();

    /**
     * Get the string representation of this object.
     *
     * @return string
     */
    public function toString();

}
