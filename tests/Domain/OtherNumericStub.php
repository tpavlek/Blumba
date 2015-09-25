<?php

namespace Depotwarehouse\Blumba\Tests\Domain;

use Depotwarehouse\Blumba\Domain\NumericValue;
use Depotwarehouse\Blumba\Domain\NumericValueInterface;
use Depotwarehouse\Blumba\Domain\ValueObjectInterface;

class OtherNumericStub implements NumericValueInterface
{

    use NumericValue;

    /**
     * @inheritDoc
     */
    public function getNumericValue()
    {
        // TODO: Implement getNumericValue() method.
    }

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
}
