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

    /**
     * Has any property of this entity been mutated since last being cleaned (serialized)?
     *
     * @return bool
     */
    public function isDirty();

    /**
     * Get the list of attributes which have changed since last being cleaned.
     *
     * @return array
     */
    public function getDirty();

    /**
     * Set the entity state to completely serialized and up-to-date.
     *
     * @return static
     */
    public function clean();

}
