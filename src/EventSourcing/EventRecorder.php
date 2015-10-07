<?php

namespace Depotwarehouse\Blumba\EventSourcing;

use Carbon\Carbon;
use Depotwarehouse\Blumba\EventSourcing\Exceptions\UnhandledEventException;
use Depotwarehouse\Blumba\ReadModel\ProjectorInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\ConnectionInterface;
use League\Event\AbstractListener;
use League\Event\EventInterface;

class EventRecorder extends AbstractListener implements EventRecorderInterface
{

    /**
     * @var \Illuminate\Database\Query\Builder
     */
    protected $eventTable;
    /**
     * @var ConnectionInterface
     */
    protected $databaseConnection;
    protected $eventProjectors;
    protected $container;

    public function __construct(ConnectionInterface $database, $eventTableName, Container $container, array $eventProjectors = [])
    {
        $this->databaseConnection = $database;
        $this->eventTable = $database->table($eventTableName);
        $this->container = $container;
        $this->eventProjectors = $eventProjectors;
    }

    public function recordThat(SerializableEventInterface $event)
    {
        $now = Carbon::now()->toDateTimeString();

        $data = [
            'eventName' => $event->getName(),
            'aggregateId' => $event->getAggregateId(),
            'eventPayload' => $event->getSerialzedPayload(),
            'timestamp' => $now
        ];

        $this->eventTable->insert($data);
    }

    public function projectThat(SerializableEventInterface $event)
    {
        if (array_key_exists($event->getName(), $this->eventProjectors)) {
            foreach ($this->eventProjectors[$event->getName()] as $projectorClass) {
                /** @var ProjectorInterface $projector */
                $projector = $this->container->{$projectorClass};

                $projector->project($event);
            }
        }
    }

    /**
     * Listen to events that come in and determine the appropriate way to handle them
     *
     * @param EventInterface $event
     *
     * @return void
     */
    public function handle(EventInterface $event)
    {
        if (!$event instanceof SerializableEventInterface) {
            throw new UnhandledEventException($event, $this);
        }

        $this->databaseConnection->beginTransaction();

        try {
            $this->recordThat($event);
            $this->projectThat($event);

            $this->databaseConnection->commit();
        } catch (\Exception $exception) {
            $this->databaseConnection->rollBack();
            throw $exception;
        }

    }
}
