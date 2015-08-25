<?php

namespace Depotwarehouse\Blumba\Tests\Domain;

use Depotwarehouse\Blumba\Domain\Entity;
use Mockery as m;

class EntityTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function test_it_calculates_correct_apply_method()
    {
        $event = new MockEventNameStubEvent();

        $entityMock = m::mock(EntityStub::class . "['applyMockEventNameStub']");

        $entityMock->shouldReceive('applyMockEventNameStub')->with($event)->once();

        $entityMock->apply($event);
    }

}
