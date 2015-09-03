<?php

namespace Depotwarehouse\Blumba\Tests\Domain;

use Depotwarehouse\Blumba\Domain\Entity;
use Depotwarehouse\Blumba\Domain\IdValueInterface;
use Depotwarehouse\Blumba\Domain\ValueObjectInterface;

class EntityStub extends Entity
{

    protected $mockId;
    /**
     * @var ValueObjectInterface
     * @serializes other_prop_name
     */
    protected $mockProperty;

    public function __construct()
    {
        $this->mockId = new IdValueStub(1);
        $this->mockProperty = new MockPropertyValueObjectStub("old_value");
    }

    public function applyMockEventNameStub(MockEventNameStubEvent $event)
    {
        $mockProperty = new MockPropertyValueObjectStub("new_value");
        $this->setAttribute("mockProperty", $mockProperty);
    }

    public function getMockProperty()
    {
        return $this->mockProperty;
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
        return $this->mockId;
    }

    /**
     * Get the string representation of this object.
     *
     * @return string
     */
    public function toString()
    {
        return $this->getId()->toString();
    }
}
