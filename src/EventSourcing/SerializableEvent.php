<?php

namespace Depotwarehouse\Blumba\EventSourcing;

use League\Event\AbstractEvent;

abstract class SerializableEvent extends AbstractEvent implements SerializableEventInterface
{
    /**
     * @inheritDoc
     */
    public function getSerialzedPayload()
    {
        return json_encode($this->getPayload());
    }


}
