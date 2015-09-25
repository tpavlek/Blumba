<?php

namespace Depotwarehouse\Blumba\Tests\Domain;

use Depotwarehouse\Blumba\Domain\NumericValue;
use Depotwarehouse\Blumba\Domain\NumericValueInterface;

class NumericValueStub implements NumericValueInterface
{

    use NumericValue;

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
