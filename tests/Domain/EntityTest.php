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
        m::close();
    }

    public function test_it_calculates_correct_apply_method()
    {
        $event = new MockEventNameStubEvent();

        $entityMock = m::mock(EntityStub::class)->makePartial();

        $entityMock->shouldReceive('applyMockEventNameStub')->with($event)->once();

        $entityMock->apply($event);
    }

    public function test_it_marks_attributes_as_dirty_when_being_set()
    {
        $event = new MockEventNameStubEvent();

        $entity = new EntityStub();

        $entity->apply($event);

        $this->assertTrue($entity->isDirty());
        $this->assertEquals([ "mock_attribute" ], $entity->getDirty());
    }

}
