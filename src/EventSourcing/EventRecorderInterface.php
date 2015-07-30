<?php

namespace Depotwarehouse\Blumba\EventSourcing;

use League\Event\EventInterface;
use League\Event\ListenerInterface;

interface EventRecorderInterface extends ListenerInterface
{

    public function handle(EventInterface $event);

    public function recordThat(SerializableEventInterface $event);

    public function projectThat(SerializableEventInterface $event);

}
