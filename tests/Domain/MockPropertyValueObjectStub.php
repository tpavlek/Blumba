<?php

namespace Depotwarehouse\Blumba\Tests\Domain;

use Depotwarehouse\Blumba\Domain\ValueObject;
use Depotwarehouse\Blumba\Domain\ValueObjectInterface;

class MockPropertyValueObjectStub extends ValueObject
{

    protected $text;

    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * @inheritDoc
     */
    protected function equalsSelf(ValueObjectInterface $otherObject)
    {
        return $this->toString() === $otherObject->toString();
    }

    /**
     * @inheritDoc
     */
    public function toString()
    {
        return (string)$this->text;
    }
}
