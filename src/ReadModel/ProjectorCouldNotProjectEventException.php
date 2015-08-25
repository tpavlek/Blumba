<?php

namespace Depotwarehouse\Blumba\ReadModel;

use Depotwarehouse\Blumba\EventSourcing\SerializableEventInterface;

class ProjectorCouldNotProjectEventException extends \Exception
{

    public function __construct(ProjectorInterface $projector, SerializableEventInterface $event)
    {
        $projectorClass = get_class($projector);
        $message = "Projector of type: {$projectorClass} does not know how to handle the event: {$event->getName()}";

        parent::__construct($message);
    }

}
