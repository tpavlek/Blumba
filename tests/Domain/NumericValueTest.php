<?php

namespace Depotwarehouse\Blumba\Tests\Domain;

class NumericValueTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testItCanSumValues()
    {
        $sum = NumericValueStub::sum(
            new NumericValueStub(1),
            new NumericValueStub(2),
            new NumericValueStub(3)
        );

        $this->assertEquals(6, $sum->getNumericValue());
    }

}
