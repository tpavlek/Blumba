<?php

namespace Depotwarehouse\Blumba\EventSourcing\Exceptions;

use League\Event\EventInterface;
use League\Event\ListenerInterface;

class UnhandledEventException extends \InvalidArgumentException
{

    protected $event;

    public function __construct(EventInterface $event, ListenerInterface $handler)
    {
        $this->event = $event;
        $message = get_class($handler) . " unable to handle {$event->getName()}";
        parent::__construct($message);
    }

}
