<?php

namespace Depotwarehouse\Blumba\Domain;

use Depotwarehouse\Blumba\EventSourcing\SerializableEventInterface;

class EntityCouldNotApplyEventException extends \Exception
{

    public function __construct(EntityInterface $entity, SerializableEventInterface $event)
    {
        $entityClass = get_class($entity);
        $message = "Entity of type: {$entityClass} does not know how to handle the event: {$event->getName()}";

        parent::__construct($message);
    }
}
