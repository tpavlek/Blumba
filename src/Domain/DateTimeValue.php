<?php

namespace Depotwarehouse\Blumba\Domain;

use Carbon\Carbon;

class DateTimeValue extends ValueObject
{

    protected $dateTime;

    public function __construct(Carbon $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public static function now()
    {
        return new self(Carbon::now());
    }

    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Compare this ValueObject to another of the same type.
     *
     * @param ValueObjectInterface $otherObject
     * @return bool
     */
    protected function equalsSelf(ValueObjectInterface $otherObject)
    {
        /** @var self $otherObject */
        return $this->getDateTime()->eq($otherObject->getDateTime());
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->getDateTime()->toDateTimeString();
    }
}
