<?php

namespace Depotwarehouse\Blumba\Tests\ReadModel;

use Depotwarehouse\Blumba\EventSourcing\EventRecorder;
use Depotwarehouse\Blumba\EventSourcing\SerializableEventInterface;
use Depotwarehouse\Blumba\ReadModel\ProjectorInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\ConnectionInterface;
use Mockery as m;

class EventRecorderTest extends \PHPUnit_Framework_TestCase
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

    public function testItProjectsEvents()
    {
        $mockConnection = m::mock(ConnectionInterface::class);
        $mockConnection->shouldReceive('table')->with("events");

        $mockContainer = m::mock(Container::class);

        $eventProjectors = [
            'MockEventClass' => [
                'MockContainerCallClass'
            ]
        ];

        $eventRecorder = new EventRecorder($mockConnection, "events", $mockContainer, $eventProjectors);

        $mockEvent = m::mock(SerializableEventInterface::class);
        $mockEvent->shouldReceive('getName')->andReturn('MockEventClass');

        $mockProjector = m::mock(ProjectorInterface::class);
        $mockProjector->shouldReceive('project')->with($mockEvent)->once();

        $mockContainer->{'MockContainerCallClass'} = $mockProjector;
        $eventRecorder->projectThat($mockEvent);
    }

}
