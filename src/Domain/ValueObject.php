<?php

namespace Depotwarehouse\Blumba\Domain;

abstract class ValueObject implements ValueObjectInterface
{

    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Compare this ValueObject to another value object that does not share the same precise type.
     *
     * @param ValueObjectInterface $otherObject
     * @return bool
     */
    protected function equalsOther(ValueObjectInterface $otherObject)
    {
        return $this->toString() === $otherObject->toString();
    }

    /**
     * Compare this ValueObject to another of the same type.
     *
     * @param ValueObjectInterface $otherObject
     * @return bool
     */
    abstract protected function equalsSelf(ValueObjectInterface $otherObject);

    /**
     * @param ValueObjectInterface $otherObject
     * @return boolean
     */
    public function equals(ValueObjectInterface $otherObject)
    {
        if ($otherObject instanceof static) {
            return $this->equalsSelf($otherObject);
        }

        return $this->equalsOther($otherObject);
    }
}
