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
        $collection = new Collection($numericValues);

        if ($collection->count() == 0) {
            throw new \InvalidArgumentException("You must provide at least one value to sum");
        }

        $returnType = get_class($collection->first());

        $itemsNotMatchingType = $collection->filter(function (NumericValueInterface $numericValue) use ($returnType) {
            return !($numericValue instanceof $returnType);
        });

        if ($itemsNotMatchingType->count()) {
            throw new \InvalidArgumentException("All arguments must be of the same type");
        }

        $sumNumeric = (new Collection($numericValues))->reduce(function ($sum, NumericValueInterface $value) {
            return $sum + $value->getNumericValue();
        }, 0);

        return new $returnType($sumNumeric);
    }
}
