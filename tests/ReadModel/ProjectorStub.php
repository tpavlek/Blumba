<?php

namespace Depotwarehouse\Blumba\Tests\ReadModel;

use Depotwarehouse\Blumba\ReadModel\Projector;
use Depotwarehouse\Blumba\Tests\Domain\MockEventNameStubEvent;
use Illuminate\Database\ConnectionInterface;

class ProjectorStub extends Projector
{

    public function projectMockEventNameStub(MockEventNameStubEvent $event)
    {

    }

    public static function initialize(ConnectionInterface $connection)
    {
        // TODO: Implement initialize() method.
    }
}
