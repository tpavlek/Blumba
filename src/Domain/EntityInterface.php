<?php

namespace Depotwarehouse\Blumba\Domain;

interface EntityInterface
{

    /**
     * Compute equality of the identity of this entity with another entity.
     *
     * @param EntityInterface $otherEntity
     * @return boolean
     */
    public function equals(EntityInterface $otherEntity);

    /**
     * @return mixed
     */
    public function getId();

}
