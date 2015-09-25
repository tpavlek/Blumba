<?php

namespace Depotwarehouse\Blumba\Tests\Domain;

use Depotwarehouse\Blumba\Domain\NumericValue;

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

    public function testItCanDetermineWhatTypeOfValueToSum()
    {
        $add1 = new NumericValueStub(1);
        $add2 = new NumericValueStub(2);

        $sum = NumericValue::sum($add1, $add2);

        $this->assertInstanceOf(NumericValueStub::class, $sum);
        $this->assertEquals(3, $sum->getNumericValue());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage All arguments must be of the same type
     */
    public function testItRejectsNonContiguousTypesOfArguments()
    {
        $add1 = new NumericValueStub(1);
        $add2 = new OtherNumericStub(2);

        NumericValue::sum($add1, $add2);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage You must provide at least one value to sum
     */
    public function testItRequiresAtLeastOneArgument()
    {
        NumericValue::sum();
    }

}
