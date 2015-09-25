<?php

namespace Depotwarehouse\Blumba\Domain;

use Illuminate\Support\Collection;

trait NumericValue
{

    /**
     * @param \Depotwarehouse\Blumba\Domain\NumericValueInterface ...$numericValues
     * @return NumericValueInterface
     */
    public static function sum(NumericValueInterface ...$numericValues)
    {
        $sumNumeric = (new Collection($numericValues))->reduce(function ($sum, NumericValueInterface $value) {
            return $sum + $value->getNumericValue();
        }, 0);

        return new self($sumNumeric);
    }
}
