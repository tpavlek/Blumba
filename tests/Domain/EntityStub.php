<?php

namespace Depotwarehouse\Blumba\Tests\Domain;

use Depotwarehouse\Blumba\Domain\Entity;
use Depotwarehouse\Blumba\Domain\IdValueInterface;

class EntityStub extends Entity
{

    public function applyMockEventNameStub(MockEventNameStubEvent $event)
    {
        $this->setAttribute("mock_attribute", "new_value");
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    /**
     * @return IdValueInterface
     */
    public function getId()
    {
        // TODO: Implement getId() method.
    }

    /**
     * Get the string representation of this object.
     *
     * @return string
     */
    public function toString()
    {
        // TODO: Implement toString() method.
    }
}
