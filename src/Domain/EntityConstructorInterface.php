<?php

namespace Depotwarehouse\Blumba\Domain;

/**
 * Interface EntityConstructorInterface
 *
 * This interface is used to encapsulate the creation of entities, which can become quite complex overall.
 *
 * The default entity construction methods take attributes in the form of an associative array, of attribute_name => value.
 *
 * These values can either be scalars, which the create methods should then construct appropriate Value Objects out of, or
 * they could be the Value Objects themselves. Checking for scalar vs. Value Object should be done on a field by field basis,
 * so you may pass a mix.
 * 
 * @package Depotwarehouse\Blumba\Domain
 */
interface EntityConstructorInterface
{

    /**
     * Given an array of attributes, reconstitute an existing entity.
     *
     * @param array $attributes
     * @return EntityInterface
     */
    public function createInstance(array $attributes);

    /**
     * Given an array of attributes, construct a new entity.
     *
     * @param array $attributes
     * @return EntityInterface
     */
    public function create(array $attributes);

}
