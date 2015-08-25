<?php

namespace Depotwarehouse\Blumba\Tests\ReadModel;

use Depotwarehouse\Blumba\Tests\Domain\MockEventNameStubEvent;
use Mockery as m;

class ProjectorTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    public function test_it_projects_the_correct_method()
    {
        $event = new MockEventNameStubEvent();

        $projectorMock = m::mock(ProjectorStub::class)->makePartial();

        $projectorMock->shouldReceive('projectMockEventNameStub')->with($event)->once();

        $projectorMock->project($event);
    }

}
