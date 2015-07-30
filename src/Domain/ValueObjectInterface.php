<?php


namespace Depotwarehouse\Blumba\Domain;


interface ValueObjectInterface
{

    /**
     * @param ValueObjectInterface $otherObject
     * @return boolean
     */
    public function equals(static $otherObject);

    /**
     * @return string
     */
    public function toString();
}
