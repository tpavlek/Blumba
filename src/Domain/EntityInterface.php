<?php

namespace Depotwarehouse\Blumba\Domain;

use Illuminate\Contracts\Support\Arrayable;

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
