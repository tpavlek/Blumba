<?php

namespace Depotwarehouse\Blumba\Domain;

use Rhumsaa\Uuid\Uuid;

abstract class EntityConstructor implements EntityConstructorInterface
{

    /**
     * Generates a unique ID for use with an Entity.
     *
     * @return string
     */
    protected function generateId()
    {
        return Uuid::uuid4()->toString();
    }

}
