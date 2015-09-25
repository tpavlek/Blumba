<?php

namespace Depotwarehouse\Blumba\Domain;

interface NumericValueInterface
{

    /**
     * Get the numeric representation of this object for arithmetic.
     *
     * @return int|float
     */
    public function getNumericValue();

    /**
     * @param \Depotwarehouse\Blumba\Domain\NumericValueInterface ...$numericValues
     * @return NumericValueInterface
     */
    public static function sum(NumericValueInterface ...$numericValues);

}
