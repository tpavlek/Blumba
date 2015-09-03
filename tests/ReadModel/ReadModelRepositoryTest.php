<?php

namespace Depotwarehouse\Blumba\Tests\ReadModel;

use Depotwarehouse\Blumba\ReadModel\ReadModelRepository;
use Depotwarehouse\Blumba\Tests\Domain\EntityStub;
use Depotwarehouse\Blumba\Tests\Domain\MockEventNameStubEvent;
use Illuminate\Database\ConnectionInterface;
use Mockery as m;

class ReadModelRepositoryTest extends \PHPUnit_Framework_TestCase
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

    public function test_it_maps_properties_via_docblock()
    {
        $repository = m::mock(ReadModelRepository::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $mockConnection = m::mock(ConnectionInterface::class);

        $entity = new EntityStub();
        $entity->apply(new MockEventNameStubEvent());

        $repository->shouldReceive('getTable')->andReturn($mockConnection);
        $mockConnection->shouldReceive('where')->andReturn($mockConnection);
        $mockConnection->shouldReceive('update')->with([ 'other_prop_name' => 'new_value'])->once();

        $repository->update($entity);
    }

}
