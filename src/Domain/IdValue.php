<?php

namespace Depotwarehouse\Blumba\Domain;

class IdValue extends ValueObject
{

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Compare this ValueObject to another of the same type.
     *
     * @param ValueObjectInterface $otherObject
     * @return bool
     */
    protected function equalsSelf(ValueObjectInterface $otherObject)
    {
        return $this->toString() === $otherObject->toString();
    }

    /**
     * @return string
     */
    public function toString()
    {
        return (string)$this->id;
    }
}
