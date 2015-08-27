<?php


namespace Depotwarehouse\Blumba\Domain;


interface ValueObjectInterface
{

    /**
     * @param ValueObjectInterface $otherObject
     * @return boolean
     */
    public function equals(ValueObjectInterface $otherObject);

    /**
     * @return string
     */
    public function toString();

    /**
     * Convert the object to a form that is representable in a database.
     *
     * @return string
     */
    public function serialize();
}
