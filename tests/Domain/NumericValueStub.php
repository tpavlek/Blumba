<?php

namespace Depotwarehouse\Blumba\Tests\Domain;

use Depotwarehouse\Blumba\Domain\NumericValue;
use Depotwarehouse\Blumba\Domain\NumericValueInterface;
use Depotwarehouse\Blumba\Domain\ValueObjectInterface;

class NumericValueStub implements NumericValueInterface
{

    use NumericValue;

    /**
     * @inheritDoc
     */
    public function equals(ValueObjectInterface $otherObject)
    {
        // TODO: Implement equals() method.
    }

    /**
     * @inheritDoc
     */
    public function toString()
    {
        // TODO: Implement toString() method.
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        // TODO: Implement serialize() method.
    }



    protected $number;

    public function __construct($number)
    {
        $this->number = $number;
    }

    /**
     * Get the numeric representation of this object for arithmetic.
     *
     * @return int|float
     */
    public function getNumericValue()
    {
        return (int)$this->number;
    }


}
