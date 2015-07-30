<?php

namespace Depotwarehouse\Blumba\EventSourcing;

use Depotwarehouse\Blumba\EventSourcing\Exceptions\UnhandledEventException;
use League\Event\AbstractListener;
use League\Event\EventInterface;

class EventRecorder extends AbstractListener implements EventRecorderInterface
{

    public function recordThat(SerializableEventInterface $event)
    {
        // TODO: Implement recordThat() method.
    }

    public function projectThat(SerializableEventInterface $event)
    {
        // TODO: Implement projectThat() method.
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

        $this->recordThat($event);
        $this->projectThat($event);
    }
}
