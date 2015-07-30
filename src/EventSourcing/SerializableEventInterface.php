<?php

namespace Depotwarehouse\Blumba\EventSourcing;

interface SerializableEventInterface
{

    /**
     * @return array
     */
    public function getPayload();

    /**
     * Get a JSON representation of the payload.
     *
     * @return string
     */
    public function getSerialzedPayload();

    /**
     * @return string
     */
    public function getAggregateId();
}
