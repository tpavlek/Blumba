<?php

namespace Depotwarehouse\Blumba\Domain;

interface Reconstituteable
{

    /**
     * Given an array of attributes, reconstitute an existing entity.
     *
     * @see EntityConstructorInterface
     * @param array $attributes
     * @return EntityInterface
     */
    public function createInstance(array $attributes);

}
