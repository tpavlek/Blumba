<?php

namespace Depotwarehouse\Blumba\Tests\Domain;

use Depotwarehouse\Blumba\EventSourcing\SerializableEvent;

class MockEventNameStubEvent extends SerializableEvent
{

    /**
     * @return array
     */
    public function getPayload()
    {
        // TODO: Implement getPayload() method.
    }

    /**
     * Get a JSON representation of the payload.
     *
     * @return string
     */
    public function getSerialzedPayload()
    {
        // TODO: Implement getSerialzedPayload() method.
    }

    /**
     * @return string
     */
    public function getAggregateId()
    {
        // TODO: Implement getAggregateId() method.
    }
}
